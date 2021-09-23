<?php

namespace Tests\Feature;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Symfony\Component\HttpFoundation\Response;

abstract class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'https://api.themoviedb.org/3/*' => function (Request $request): PromiseInterface {
                try {
                    $body = $this->fixture(
                        Str::after(parse_url($request->url(), PHP_URL_PATH), '/3/'),
                        parse_url($request->url(), PHP_URL_QUERY)
                    );
                } catch (FileNotFoundException $e) {
                    return Http::response([
                        'error' => [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ],
                    ], Response::HTTP_NOT_FOUND);
                }

                return Http::response($body, Response::HTTP_OK);
            },
        ]);
    }

    protected function tearDown(): void
    {
        foreach (Http::recorded() as $record) {
            /**
             * @var \Illuminate\Http\Client\Request $request
             * @var \Illuminate\Http\Client\Response $response
             */
            [$request, $response] = $record;

            if ($response->successful()) {
                $filepath = $this->fixturePath(
                    Str::after(parse_url($request->url(), PHP_URL_PATH), '/3/'),
                    parse_url($request->url(), PHP_URL_QUERY)
                );

                File::ensureDirectoryExists(dirname($filepath));
                File::put(
                    $filepath,
                    json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                );
            }
        }

        parent::tearDown();
    }

    public function fixture(string $path, ?string $query = null): array
    {
        $json = File::get($this->fixturePath($path, $query));

        return json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    }

    protected function fixturePath(string $path, ?string $query = null): string
    {
        return Str::of($path)
            ->trim('/')
            ->prepend(__DIR__.'/../fixtures/')
            ->when($query, function (Stringable $path, string $query): Stringable {
                $data = [];
                parse_str($query, $data);
                ksort($data);

                return $path->append('/'.http_build_query($data));
            })
            ->finish('.json');
    }
}
