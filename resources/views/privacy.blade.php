@extends('layouts.app')

@section('content')
<div class="centered-info-container">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Privacyverklaring</h2>
        </div>

        <div class="card-body">
            <p>Mammon-ee is verantwoordelijk voor de verwerking van persoonsgegevens zoals weergegeven in deze privacyverklaring.</p>

            <h4>Contactgegevens</h4>

            <p>
                Website: <a class="text-primary" href="{{ config('app.url') }}">{{ config('app.url') }}</a>
                <br />
                E-mail: <a class="text-primary" href="mailto:{{ config('mail.address_info') }}">{{ config('mail.address_info') }}</a>
            </p>

            <h4>Persoonsgegevens die wij verwerken</h4>

            <p>
                Mammon-ee verwerkt uw persoonsgegevens doordat u gebruik maakt van onze diensten en/of omdat u deze zelf aan ons verstrekt. Hieronder vindt u een overzicht van de persoonsgegevens die wij verwerken:
                <ul>
                    <li>E-mailadres</li>
                    <li>Bankrekeningnummer</li>
                    <li>Overige persoonsgegevens die u actief verstrekt bijvoorbeeld door een profiel op deze website aan te maken</li>
                </ul>
            </p>

            <h4>Bijzondere en/of gevoelige persoonsgegevens die wij verwerken</h4>

            <p>Onze website en/of dienst heeft niet de intentie gegevens te verzamelen over websitebezoekers die jonger zijn dan 16 jaar. Tenzij ze toestemming hebben van ouders of voogd. We kunnen echter niet controleren of een bezoeker ouder dan 16 is. Wij raden ouders dan ook aan betrokken te zijn bij de online activiteiten van hun kinderen, om zo te voorkomen dat er gegevens over kinderen verzameld worden zonder ouderlijke toestemming. Als u er van overtuigd bent dat wij zonder die toestemming persoonlijke gegevens hebben verzameld over een minderjarige, neem dan contact met ons op via info@mammonee.nl, dan verwijderen wij deze informatie.</p>

            <h4>Met welk doel en op basis van welke grondslag wij persoonsgegevens verwerken</h4>

            <p>
                Mammon-ee verwerkt uw persoonsgegevens voor de volgende doelen:
                <ul>
                    <li>U de mogelijkheid te bieden een account aan te maken</li>
                    <li>U te informeren over wijzigingen van onze diensten en producten</li>
                </ul>
            </p>

            <h4>Hoe lang we persoonsgegevens bewaren</h4>

            <p>Mammon-ee bewaart uw persoonsgegevens niet langer dan strikt nodig is om de doelen te realiseren waarvoor uw gegevens worden verzameld.</p>

            <h4>Delen van persoonsgegevens met derden</h4>

            <p>Mammon-ee verstrekt uitsluitend aan derden en alleen als dit nodig is voor de uitvoering van onze overeenkomst met u of om te voldoen aan een wettelijke verplichting.</p>

            <h4>Cookies, of vergelijkbare technieken, die wij gebruiken</h4>

            <p>Mammon-ee gebruikt alleen technische en functionele cookies. En analytische cookies die geen inbreuk maken op uw privacy. Een cookie is een klein tekstbestand dat bij het eerste bezoek aan deze website wordt opgeslagen op uw computer, tablet of smartphone. De cookies die wij gebruiken zijn noodzakelijk voor de technische werking van de website en uw gebruiksgemak. Ze zorgen ervoor dat de website naar behoren werkt en onthouden bijvoorbeeld uw voorkeursinstellingen. Ook kunnen wij hiermee onze website optimaliseren. U kunt zich afmelden voor cookies door uw internetbrowser zo in te stellen dat deze geen cookies meer opslaat. Daarnaast kunt u ook alle informatie die eerder is opgeslagen via de instellingen van uw browser verwijderen.</p>

            <h4>Gegevens inzien, aanpassen of verwijderen</h4>

            <p>U heeft het recht om uw persoonsgegevens in te zien, te corrigeren of te verwijderen. Dit kunt u zelf doen via de persoonlijke instellingen van uw account. Daarnaast heeft u het recht om uw eventuele toestemming voor de gegevensverwerking in te trekken of bezwaar te maken tegen de verwerking van uw persoonsgegevens door ons bedrijf en heeft u het recht op gegevensoverdraagbaarheid. Dat betekent dat u bij ons een verzoek kunt indienen om de persoonsgegevens die wij van u beschikken in een computerbestand naar u of een ander, door u genoemde organisatie, te sturen. Wilt u gebruik maken van uw recht op bezwaar en/of recht op gegevensoverdraagbaarheid of heeft u andere vragen/opmerkingen over de gegevensverwerking, stuur dan een gespecificeerd verzoek naar info@mammonee.nl. Om er zeker van te zijn dat het verzoek tot inzage door u is gedaan, vragen wij u een kopie van uw identiteitsbewijs bij het verzoek mee te sturen. Maak in deze kopie uw pasfoto, MRZ (machine readable zone, de strook met nummers onderaan het paspoort), paspoortnummer en Burgerservicenummer (BSN) zwart. Dit ter bescherming van uw privacy. Mammon-ee zal zo snel mogelijk, maar in ieder geval binnen vier weken, op uw verzoek reageren. Mammon-ee wil u er tevens op wijzen dat u de mogelijkheid hebt om een klacht in te dienen bij de nationale toezichthouder, de Autoriteit Persoonsgegevens. Dat kan op <a class="text-primary" href="https://autoriteitpersoonsgegevens.nl/nl/zelf-doen/privacyrechten/klacht-indienen-bij-de-ap" noopener noreferrer>autoriteitpersoonsgegevens.nl</a>.</p>

            <h4>Hoe wij persoonsgegevens beveiligen</h4>

            <p>Mammon-ee neemt de bescherming van uw gegevens serieus en neemt passende maatregelen om misbruik, verlies, onbevoegde toegang, ongewenste openbaarmaking en ongeoorloofde wijziging tegen te gaan. Als u de indruk heeft dat uw gegevens niet goed beveiligd zijn of er aanwijzingen zijn van misbruik, neem dan contact met ons op via <a class="text-primary" href="mailto:{{ config('mail.address_info') }}">{{ config('mail.address_info') }}</a>.</p>
        </div>
    </div>
</div>
@endsection
