Dit document beschrijft het display script  MetNo weer voorspellingen


mappen:
	cache			om de verwerkte / gevonverteerde xml op te slaan
	img				afbeeldingen zoals: sun-up sun-down
	javascripts		jquery.js	highcharts.js  tabber.js
	lang			de taal / vertaal bestanden 
	otherScripts	wsLangFunctions.php	wsFunctions.php wsIconUrl.php 
	windicons		de witte windiconen
	windiconsSmall	kleine blauwe wind iconen
	wsIcons			iconen voor bewolking / weer condities

Stylesheets:
	metno.css			css om de uitvoer correct weer te geven

scripts:
	metnoSettings.php		Algemene instellingen (u zet ze 1 keer) die niet uitvoer gerelateerd zijn
	metnoCreateArr.php		Leest xml en genereert een array ter verdere verwerking
	metnoGenerateHtml.php	Gebruikt de array uit de voorgaande stap en maakt de html stukken (in strings) die door de print scripts worden afgedrukt

example scripts
	printDemo.php				Aangepaste versie van  printFull.php om wat extra informatie af te beelden NIET voor produktie gebruiken
	printFull.php				drukt alle beschikbare informatie af
	printSmall.php				drukt veel informatie af maar in een zo klein mogelijk gebied
	printTop.php				alleen de iconen worden afgebeeld
	
	wxmetnoFcstpage.php			saratoga pagina die wordt aangeroepen vanuit het Saratoga menu
	printSaratoga.php			settings en afdruk script wat wordt gebruikt in de Saratoga template

Installeer het script:
======================
1. Zet de folder  (wsMetNoSA) in de root van uw website.
	U kunt de naam van de folder aanpassen. Daar zijn geen veranderingen in de settings voor nodig.
	Maar het veiligste is: 
		Installeer standaard in de root en TEST eerst (step2).
		Als alles werkt (wat meteen zou moeten) kunt u de naam veranderen en de plaats van de map wijzigen.

2. Test het script met:www.yourwebsite.com/wsMetNoSA/printDemo.php
                    =============================================
    Als er een foutboodschap komt: Controleer de schrijfpermissies van de cache map (wsYrnoSA/cache)

Alles moet ZONDER aanpassingen direct werken. De naam klopt niet natuurlijk en het zijn ook niet uw gegevens.
Die gaan we zo dadelijk aanpassen en instellen in de settings!

Installatie in de Saratoga template:
===================================
Nadat het script correct werkt kunt u 
1. het wxmetnoFcstpage.php kopieren vanuit wsMetoNoSA map naar de root van de Saratoga template.
Meestal is dat ook de root van uw webserver.
Dit script roept het printSaratoga.php script aan. Hierin worden nog een paar settings ingesteld die niet door de Saratoga template al gezet zijn.
Daarna wordt de data verwerkt en afgedrukt.
Bij de instellingen hieronder worden een aantal instellingen genoemd die niet voor de Saratoga verise van dit script nodig zijn.
Dat kunt u zien doordat die instellingen al van een commentaar teken op de eerste positie voorzien zijn.

U kunt het script testen door www.uwwebsite.com/wxmetnoFcstpage.php in te typen in de browser of eerst 
2.  een menu entry te maken 

3. er zijn enkele vertalingen extra nodig die niet in de standaard language files van Saratoga zijn meggenomen.
Kopieer de inhoud van wsLanguage-nl-local.txt naar het einde van uw eigen Saratoga versie language-nl-local.txt in de root van Saratoga.

Settings en andere veranderingen:
=================================
Als u wijzigingen maakt:
Ook al is het maar 1 wijziging: MAAK EERST DE CACHE LEEG. (wsMetNoSA/cache).
In de cache wordt de voorbewerkte =vertaalde en met de eenheden aangepaste xml opgeslagen.
Dus er kunnen vreemde resultaten optreden als u de settings aanpast zonder de xml opnieuw te laden en te verwerken.

Eenmalig dienen de instellingen in metnoSettings.php te worden ingesteld/aangepast.
========                           ===============
De regels met ##### in de opmerkingen moet u in ieder geval bekijken en aanpassen aan uw situatie.

1. Controleer de keuze voor de iconen: YrNo iconen (standaard) or the KDE iconen
	$SITE['yrnoIconsOwn']	= true;					// #####	use original yrno icons or our general icons (false)

2. 	De gegevens van uw website, uw weerstation en de omgeving
	$SITE['yourArea']		= 'yourArea';			// #####	example	Leuven
	$SITE['organ']			= 'yourStationName';	// #####			Weerstation Leuven
	$SITE['siteUrl']		= 'your website url';	// #####			www.weerstation-leuven.be

3. Dit script laadt de verwachting op basis van de coordinaten. Staat nu ingesteld op Leuven Belgie.
	Wordt ook gebruikt om de juiste zons-opkomst -ondergang en de daglengte te kunnen berekenen
	$SITE['latitude']		= '50.89518';			// #####	North=positive, South=negative decimal degrees
	$SITE['longitude']		= '4.69741';			// #####	East=positive, West=negative decimal degrees

4. Controleer of de eenheden voor temperatuur e.d. overeenkomen met de andere gegevens op uw website.
	Het script past de waardes aan van de YrNo eenheden naar de door u gewenste eenheden.
	Alleen de windsterkte is in bft en kan dus per definitie NIET omgerekend worden naar een andere eenheid.
	$SITE['uomTemp']		= '&deg;C';		// ='&deg;C', ='&deg;F'
	$SITE['uomRain']		= ' mm';		// =' mm', =' in'
	$SITE['uomWind'] 		= ' km/h';		// =' km/h', =' kts', =' m/s', =' mph'
	$SITE['uomBaro']		= ' hPa';		// =' hPa', =' mb', =' inHg'
	$SITE['uomSnow']		= ' cm';		// =' cm', =' in'
	$SITE['uomDistance']	= ' km';		// =' km', = ' mi'  used for visibillity 
	
5. U kunt ook het formaat van datums en tijden aanpassen zodat ze passen bij de rest van uw website
	$SITE['timeFormat']		= 'd-m-Y H:i';	// 31-03-2012 14:03
	$SITE['timeOnlyFormat']	= 'H:i';		// Euro format hh:mm  (hh=00..23);
	$SITE['dateOnlyFormat']	= 'd-m-Y';		// for 31-03-2013
	$SITE['dateLongFormat']	= 'l d F Y';	// Thursday 3 january 2013
	
Er zijn nog meer instellingen die u normaal gesproken niet hoeft te wijzigen.

De instellingen in  printFull.php past u eerst 1 keer aan.
                    =================              ========
Daarna kopieert u het script onder een nieuwe naam als u het met andere instellingen wilt gebruiken.
       ========                        ===========               ===================
Voor een zo compact mogelijke weergave is er ook een printSmallPage.php (met de groene achtergrond).
die kunt u natuurlijk ook als startpunt van uw aanpassingen nemen. En de printTop.php voor alleen iconen.

1. Maar opnieuw: test vaak genoeg en na iedere (paar) wijzigingen!

2. Hoewel deze instellingen de conversie van de xml niet beinvloden:
	is het verstandiger om eerst de cache leeg te maken als u onverwachte resultaten krijgt!

3. Foutboodschappen staan standaard ON. 
	Er horen immers geen fouten voor te komen. U kunt indien gewenst ze hier afzetten.
	$errorMessages			= true;			// true: error messages for all php errors. false: supprres the messages

4. Standaard genereert het script de benodigde extra html (body head e.d.).
	Als u het script in een eigen pagina integreert, zet u de generatie van html hier af.
	$includeHead			= true; 		// <head><body><css><scripts> are loaded

5. De breedte en de kleur kan hier worden ingesteld.
	$colorClass				= 'pastel';		// pastel green blue beige orange 
	$pageWidth				= '800px';		// set do disired width 999px  or 100%

6. De scripts genereren 3 stuks verschillende uitvoer met verwachtingen en een tweetal regels met uw naam en de update tijden.
	Hier stelt u in welke delen u wilt afbeelden op het scherm.
	Voer de demo pagina uit om de verschillende stukken te zien met wat toelichting.
	$updateTimes			= true;			// two lines with recent file / new update information
	$iconGraph				= true;			// icon type header  with 2 icons for each day (12 hours data)
	$chartsGraph			= true;			// high charts graph one colom for every 3 hours
	$yrnoTable				= true;			// table with one line for every 3 hours

7. U kunt hier aangeven hoeveel kolommen / iconen u voor de twee erste uitvoeren wilt.
	standaard staat dat op 8 kolommen en dit is gelijk aan 2 dagen (4 dagdelen van 6 uur).
	$topCount				= 8;			// max nr of day-part forecasts in icons or graph

Dit zijn de belangrijkste instellingen in het begin van het script.	
In het midden van het script worden de 4 delen afgebeeld naar het scherm en kunt u allerlei kleine en grote aanpassingen doen.

Er zijn instellingen voor marges, breedte enzovoort.
Pas alles aan zoals u wilt. Enige html en css kennis is wel nodig!

Als u na iedere wijziging een test uitvoert weet u bij een fout precies waar u moet zoeken.
Na 10 wijzigingen pas een test uitvoeren en het gaat dan fout: veel sterkte, daar komt bijna geen mens uit. 

Dus vergeet niet vaak te testen EN leeg de cache als u onverwachte fouten krijgt.

Dit staat in iedere  yr.no  of metno xml die u downloadt via dit script:
<!--
In order to use the free weather data from yr no, you HAVE to display 
the following text clearly visible on your web page. The text should be a 
link to the specified URL.
-->
U voldoet aan deze voorwaarde als u de paginas metnoFullPage.php and the demoFull.php gebruikt.
Onderaan de pagina wordt deze credit string ( $creditString ) afgedrukt.

Als u eigen pagina's maakt moet u de  $creditString  op de pagina afbeelden.

Voor aanpassingen op de scripts en de template kijk  op  www.weerstation-leuven.be

Succes, Wim van der Kuil




  









