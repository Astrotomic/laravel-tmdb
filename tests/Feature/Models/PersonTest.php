<?php

use Astrotomic\PhpunitAssertions\ArrayAssertions;
use Astrotomic\Tmdb\Enums\Gender;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Person;
use PHPUnit\Framework\Assert;

it('maps data from tmdb', function (): void {
    $person = new Person(['id' => 6384]);
    $person->updateFromTmdb();

    Assert::assertSame(6384, $person->id);
    Assert::assertSame('Keanu Reeves', $person->name);
    Assert::assertFalse($person->adult);
    ArrayAssertions::assertEquals([
        'Киану Ривз',
        '키아누 리브스',
        'キアヌ・リーブス',
        'เคอานู รีฟส์',
        '基努·里维斯',
        'קיאנו ריבס',
        'Keanu Charles Reeves',
        'Κιάνου Ριβς',
        'Κιάνου Τσαρλς Ριβς',
        'Кіану Рівз',
        'كيانو ريفز',
        '基努·李維',
    ], $person->also_known_as);
    Assert::assertTrue($person->birthday?->isSameDay('1964-09-02'));
    Assert::assertNull($person->deathday);
    Assert::assertSame(Gender::MALE, $person->gender);
    Assert::assertNull($person->homepage);
    Assert::assertSame('nm0000206', $person->imdb_id);
    Assert::assertSame('Acting', $person->known_for_department);
    Assert::assertSame('Beirut, Lebanon', $person->place_of_birth);
    Assert::assertSame('/rRdru6REr9i3WIHv2mntpcgxnoY.jpg', $person->profile_path);
    Assert::assertSame(51.394, $person->popularity);
    Assert::assertSame("In Toronto spielte Reeves am Stadttheater vorwiegend Stücke von Shakespeare und sammelte so Bühnenerfahrung. Seine ersten Fernseh- und Kinoauftritte hatte er ab 1979 in verschiedenen Low-Budget-Produktionen. Sein Filmdebüt gab er in der kanadischen Produktion Dream To Believe, bei welcher er auch die für Newcomer so begehrte Union Card der Screen Actors Guild (genannt SAG, eine Schauspiel-Gilde) erwarb, ohne die kaum jemand im Showgeschäft zu wirklich guten Rollen kam. 1986 verließ er Kanada mit 3000 US$, einem alten Volvo und der Adresse seines Stiefvaters Paul Aaron. Zunächst spielte er die Hauptrolle in Der Prinz von Pennsylvania als Sohn von Fred Ward. Mit der Rolle des trotteligen Teenagers Ted in Bill & Teds verrückte Reise durch die Zeit gelang ihm der Durchbruch in Hollywood. Weltbekannt wurde er aber erst 1994 als wagemutiger Polizist im Blockbuster Speed, der auch seiner Filmpartnerin Sandra Bullock den Durchbruch bescherte. Fortan erhielt er Gagen in Millionenhöhe, blieb aber vielen Kritikern ein Dorn im Auge, die seine Schauspielkunst als hölzern und ausdruckslos bezeichnen. Hollywood allerdings machte ihm ein Filmangebot nach dem anderen. Reeves konnte es sich 1997 leisten, das Angebot für Speed 2 auszuschlagen und stattdessen mit seiner Band Dogstar auf Tour zu gehen.\n\nDie Rolle des Computerhackers Neo in der Matrix-Trilogie (vier Oscars) katapultierte ihn 1999 in den Olymp der höchstbezahlten Hollywoodstars. Der ursprünglich für diese Rolle favorisierte Will Smith entschied sich für den Film Wild Wild West.[2] Die negative Kritik an Reeves wurde seitdem spärlicher. Mit Rollen wie der des brutalen Rednecks in The Gift oder des verliebten Arztes in Was das Herz begehrt erntete er viel Lob.\n\nBei den 62. Internationalen Filmfestspielen in Berlin stellte Reeves seinen, in Kritikerkreisen viel beachteten, Film Side by Side vor.[3] Die Dokumentation beschäftigt sich mit der Frage, welche Veränderungen sich beim Filmemachen durch die digitale Technik ergeben. Im deutschsprachigen Raum existiert eine gekürzte Fassung dieser Dokumentation unter dem Titel „Kino reloaded“, welche Ende 2012 beim Sender ServusTV ihre Fernsehpremiere hatte.\n\n2013 gab Reeves mit dem Film Man of Tai Chi sein Regiedebüt. Der Film wurde vollständig in China gedreht und Reeves übernahm die Rolle des Antagonisten.\n\nReeves wird in der deutschen Synchronisation seiner Filme meist von Benjamin Völz gesprochen.", $person->biography);
});

it('person provides a profile', function (): void {
    $person = Person::query()->find(6384);

    expect($person->profile())
        ->toBeInstanceOf(Poster::class)
        ->url()->toBeUrl('https://image.tmdb.org/t/p/w780/rRdru6REr9i3WIHv2mntpcgxnoY.jpg');
});

it('loads all movie credits for person', function () {
    $credits = Person::query()->with('movie_credits')->findOrFail(6384)->movie_credits;

    expect($credits)
        ->toHaveCount(136)
        ->each->toBeInstanceOf(Credit::class);
});

it('loads first page of trending movies', function (): void {
    $persons = Person::trending(20);

    expect($persons)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Person::class);

    expect(requests('trending/person/day'))
        ->toHaveCount(1);
});

it('loads several pages of trending movies', function (): void {
    $persons = Person::trending(60);

    expect($persons)
        ->toHaveCount(60)
        ->each->toBeInstanceOf(Person::class);

    expect(requests('trending/person/day'))
        ->toHaveCount(3);
});
