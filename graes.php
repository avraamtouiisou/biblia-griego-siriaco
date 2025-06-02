<?php
//parte de la logica a implementar
$textoGriego = 'Εἰ δέ τις ὑμῶν λείπεται σοφίας, αἰτείτω παρὰ τοῦ διδόντος θεοῦ πᾶσιν ἁπλῶς καὶ μὴ ὀνειδίζοντος καὶ δοθήσεται αὐτῷ';
$textoGriego = normalizeAccent(mb_strtolower($text, 'UTF-8'));

// Eliminar signos de puntuación y convertir a minúsculas para una búsqueda más robusta
$textoLimpio = preg_replace('/[[:punct:]]/u', '', strip_tags($textoGriego));
$palabras = preg_split('/\s+/u', trim($textoLimpio));

$acentosGriego = [
    'ά' => 'α', 'έ' => 'ε', 'ή' => 'η', 'ί' => 'ι', 'ό' => 'ο', 'ύ' => 'υ', 'ώ' => 'ω',
    'ὰ' => 'α', 'ὲ' => 'ε', 'ὴ' => 'η', 'ὶ' => 'ι', 'ὸ' => 'ο', 'ὺ' => 'υ', 'ὼ' => 'ω',
    'ᾶ' => 'α', 'ῆ' => 'η', 'ῖ' => 'ι', 'ῦ' => 'υ', 'ῶ' => 'ω',
    'ᾳ' => 'α', 'ῳ' => 'ω', 'ΰ' => 'υ', 'ϊ' => 'ι', 'ϋ' => 'υ', 'ΐ' => 'ι',
    'ἀ' => 'α', 'ἁ' => 'α', 'ἐ' => 'ε', 'ἑ' => 'ε', 'ἠ' => 'η', 'ἡ' => 'η',
    'ἰ' => 'ι', 'ἱ' => 'ι', 'ὀ' => 'ο', 'ὁ' => 'ο', 'ὐ' => 'υ', 'ὑ' => 'υ',
    'ὠ' => 'ω', 'ὡ' => 'ω',
    'ᾲ' => 'α', 'ᾳ' => 'α', 'ᾴ' => 'α', 'ᾶ' => 'α', 'ᾷ' => 'α',
    'ῂ' => 'η', 'ῃ' => 'η', 'ῄ' => 'η', 'ῆ' => 'η', 'ῇ' => 'η',
    'ῢ' => 'υ', 'ΰ' => 'υ', 'ῧ' => 'υ', 'ῧ' => 'υ',
    'ῤ' => 'ρ', 'ῥ' => 'ρ',
    // ... podríamos necesitar más combinaciones ...
    'ἦ' => 'η', 'ὕ' => 'υ', 'ἶ' => 'ι', 'ὅ' => 'ο', 'ὤ' => 'ω', 'ῷ' => 'ω', 'ὗ' => 'υ', 'ἔ' => 'ε', 'ὥ' => 'ω', 'ἄ' => 'α', 'ἤ' => 'η', 'ὃ' => 'ο', 'ἴ' => 'ι', 'ὔ' => 'υ', 'ἵ' => 'ι', 'ὢ' => 'ω', 'ὖ' => 'υ', 'ἢ' => 'η', 'ὄ' => 'ο', 'ἕ' => 'ε', 'ἃ' => 'α', 'ἓ' => 'ε', 'ἅ' => 'α', 'ἂ' => 'α',
    //more
    'ᾠ' => 'ω', 'ἣ' => 'η', 'ᾐ' => 'η', 'ὧ' => 'ω'
];

$textoGriegoSinAcentos = str_replace(array_keys($acentosGriego), array_values($acentosGriego), $textoGriego);
$textoGriegoConSigmaArcaica = str_replace(['σ', 'ς', 'Σ'], ['ϲ', 'ϲ', 'Ϲ'], $textoGriegoSinAcentos);

echo '<div class="d-block mb-4">';
echo '<tx class="d-block text-secondary-emphasis" style="text-transform:uppercase;">'.$verse.' '.$textoGriegoConSigmaArcaica.'</tx>'.PHP_EOL;
echo '<tb class=d-block><a class=d-inline href="x.php?book='.$booko.'&chapter='.$chapter.'&verse='.$verse.'&resources%5B%5D=tanslg&filtro=">'.$verse.'</a>'.' <t class=d-inline>'.$textoGriego.'</t></tb>'.PHP_EOL;

$sArray = [];
$mArray = [];
$iArray = [];
$gArray = [];
$rArray = [];
$l_uArray = [];
$nextA = [];
foreach ($palabras as $id => $palabra) {
    try {
        $dbFile = $path.'';
        $pdo = new PDO("sqlite:" . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
    $sqld = "SELECT * FROM lxx WHERE gr = '".$palabra."'";
    $stmt = $pdo->query($sqld);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $s = ($spanishdVar); //
        //este codigo debe comentarse despues de que se eliminar cualquier pattern en la traduccion contextual y comprobarse
        $sArray[] = $s;
        $mArray[] = !empty($morpd);
        $iArray[] = $id;
        $gArray[] = $palabra;
        $l_uArray[] = array('lex' => $lex, 'unacc' => $unacc);
        //echo $s;
    } else if (preg_match('/\d/', $palabra)) {
        $sArray[] = "<s class=text>$palabra</s>";
        $mArray[] = 'NUM';
        $iArray[] = $id;
        $gArray[] = $palabra;
        $l_uArray[] = array('lex' => '', 'unacc' => '');
    } else {
        $sArray[] = "<span class=text-danger>NaN</span>";
        $mArray[] = 'NaN';
        $iArray[] = $id;
        $gArray[] = $palabra;
        $l_uArray[] = array('lex' => '', 'unacc' => '');
    }
    $pdo = null;
}

$fGA = false;
$oti = false;
foreach ($sArray as $ids => &$su) {
    //no vas a imprimir los X sino antes de la palabra anterior

    //cambiar ubicacion de preposicion
    if (isset($mArray[$ids+1]) && $mArray[$ids+1] == 'X') {
        $postS = $sArray[$ids+1];
        $thisS = $sArray[$ids];
        $sArray[$ids+1] = $thisS;
        $sArray[$ids] = $postS;

        $postM = $mArray[$ids+1];
        $thisM = $mArray[$ids];
        $mArray[$ids+1] = $thisM;
        $mArray[$ids] = $postM;

        $postI = $iArray[$ids+1];
        $thisI = $iArray[$ids];
        $iArray[$ids+1] = $thisI;
        $iArray[$ids] = $postI;

        $postG = $gArray[$ids+1];
        $thisG = $gArray[$ids];
        $gArray[$ids+1] = $thisG;
        $gArray[$ids] = $postG;

        $postLU = $l_uArray[$ids+1];
        $thisLU = $l_uArray[$ids];
        $l_uArray[$ids+1] = $thisLU;
        $l_uArray[$ids] = $postLU;
    }

    $sFo = $sArray[$ids];

    //mejor solucion. esto es porque a veces el nominativo en genitivo no se encuentra directamente al lado del articulo genitivo sino varias palabras posteriores como en apocalipsis 3:7
    if (in_array($mArray[$ids], array('RA.GSN', 'RA.GSF', 'RA.GSM'))) {
        $fGA = true;
        $gn = definiteArticle(str_replace(' ', '', strip_tags($sArray[$ids+1])));
        //condicional en desarrollo. el primero cuando hay una pakabra anterior que tenga funcion de de. el segundo cuando el siguiente es nominativo. el ultimo solo marca el uso del genitivo de.
        //otro motivo mas. van tres, para tomar en cuenta el genero del genitivo, en este caso, para saber cual es el definite article que debemos usar
        //josue 8:22 εως
        if ($sArray[$ids-1] == 'de' || $l_uArray[$ids-1]['lex'] == 'δια' || $l_uArray[$ids-1]['lex'] == 'εως')
            $sFo = "[".$gn."";
        else if (in_array($mArray[$ids+1], array('N.GSF', 'N.GSM', 'N.GSN')))
            $sFo = "[".$su.' '.$gn."";
        else
            $sFo = "[".$su.""/*.$sArray[$ids+1]*/;
    }

    //cuando encuentras un verbo en infinitivo o un articulo en cualquier caso. faltaria agregar mas verbos, oti y pros
    if (in_array($mArray[$ids], array('V.PAN', 'RA.ASN', 'RA.ASF', 'RA.ASM', 'RA.NSM', 'V.AMN', 'V.AAN')) && $fGA == true) {
        //V.AMN tambien?
        $fGA = false;
        //V.AMN infinitivo + para si aoristo infinitivo medio
        $sFo = "]".$su;
    }

    //incluido los N.DSM nombres en dativo que tienen misma forma en genitivo.
    if (in_array($mArray[$ids], array('N.GSN', 'N.GSM', 'N.GSF', 'N-VSM-T', 'N.DSM')) && $fGA == true) {
        $fGA = false;
        //dĒ una vez se elimine esa palabra debe de eliminarse la siguiente linea y asignar su a sfo
        //$new_html_string = str_replace('dĒ', '×', $su);
        $new_html_string = $su;
        $sFo = /*$gn.' '.*/$new_html_string.'] ';
    }

    //aqui parece haber de todo pero parece tener sentido
    //A.GSN
    // pienso que tambien plural N.GPM porque los articulos en pluram no van aqui sino en los articulos
    //pienso que la regla que ya se menciona luego con el nombre de genero de la morfologias para hacer referencia a que se debe tener en cuenta el genero del genero es importante. en este caso del genitivo para saber a quien se refiere, en este caso si se antepone Δε o no N.GSF seguido de N.GSM o nombres personales mateo 1:1
    if (in_array($mArray[$ids], array('A.GPN', 'N.GSM', 'RD.GSF', 'RD.GSN', 'N.GSN', 'N.GSF', 'A.GSN', 'RD.GPM', 'N.GPM', 'N.GPN')) && $sArray[$ids-1] !== 'de' && !in_array($mArray[$ids-1], array('RA.GSN', 'A.GSN', 'RA.GSF'))) {
        $sFo = "Δε".$su;
    }

    if (in_array($l_uArray[$ids]['lex'], array('αγιαζω', 'καλεω'))) {
        $oti = true;
        $sFo = $su.'!';
    }

    //me refiero a esto cuando digo anteriormente que esta de mas
    if ($l_uArray[$ids]['unacc'] == 'οτι' && $oti == true) {
        $sFo = 'pør'.$su;
        $oti = false;
    }

    //RA.GPN porque antes va un adjetivo que tiene como prefijo de
    if (in_array($mArray[$ids], array('RA.GPN')) && in_array($mArray[$ids-1], array('A.GPN'))) {
        $sFo = 'løs';

        //A.GPN para plurales normales genitivos. estos dos condicionales se podrian unir
    } else if (in_array($mArray[$ids], array('RA.GPN')) && in_array($mArray[$ids+1], array('N.GPF', 'N.GPN', 'N.GPM'))) {
        if ($sArray[$ids-1] == 'de')
            $sFo = "løs";
        else
            $sFo = 'dεløs';
    }

    //$pattern = '/<sp[^>]*>(.*?)<\/sp>/';
    //lo mismo que tres antes pero para plurales? tal vez este tambien se tenga que eliminar
    if (in_array($mArray[$ids], array('N.GPN')) && isset($mArray[$ids-1]) && in_array($mArray[$ids-1], array('RA.GPN'))) {
        //($mArray[$ids-1] == 'RA.GSN' || $mArray[$ids-1] == 'RA.GSF')
        //dĒ la
        $new_html_string = str_replace('de', '×', $su);
        //aqui igual que anteriormente debe quitarse el articulo y ponerse con codigo plural
        $sFo = $new_html_string.' ';
    }

    // cambiar y por e en caso de que la siguiente palabra suene i
    if (str_replace(' ', '', strip_tags($su)) == "y" && isset($sArray[$ids+1]) && (str_starts_with(strip_tags($sArray[$ids+1]), "hi") || str_starts_with(strip_tags($sArray[$ids+1]), "i"))) {
        //se debe corregir porque se queda por fuera
        $sFo = 'ε<!--<s class=d-inline-block>'.$su.'</s>-->';
    }

    //$pattern = '/<sp[^>]*>(.*?)<\/sp>/';
    // El reemplazo de los a "ellos"
    if ($mArray[$ids] == 'RA.NPM' && isset($mArray[$ids+1]) && $mArray[$ids+1] == 'V.AAI3P') {
        //$new_html_string = preg_replace($pattern, 'ellos*', $su);
        //$sArray[$ids] = ' '.$new_html_string.' ';
        $sFo = 'ellos*';
    }

    //Verb: Fut Act Infin
    if ($mArray[$ids] == 'V.FAN') {
        //$generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'haberDe'.$su;
    }

    //esto para verbos V.APN con los que hay que -> ser + participio pasado
    if ($mArray[$ids] == 'V.APN') {
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'ser '.procesarFraseConVerboEnParticipio($su, $generoVP) ?? 'NeN';
    }

    //Verbo: Futuro Participio Medio
    //de los que estarán por suceder para posesivo ej. V.FMPGSM
    if ($mArray[$ids] == 'V.FMP') {
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'queEstara(n)Por'.procesarFraseConVerboEnParticipio($su, $generoVP) ?? 'NeN';
    }

    //esto para verbos V.PPP los que estan siendo + participio pasado
    //siendo asi faltaria hacer mas condicionales o mejorar estos dos para el singular y plural dependiendo del genero y caso gramatical: dativo, acusativo, genitivo
    if ($mArray[$ids] == 'V.PPPNPM' || $mArray[$ids] == 'V.PPPNPF') {
        //aqui habria que mirar si aplica por el morfema o por el articulo anterior. yo digo que por el articulo anterior. no tendria sentido que no fuera asi
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'queEstanSiendo'.procesarFraseConVerboEnParticipio($su, $generoVP) ?? 'NeN';
    }

    //esto para verbos V.XPPAPN son pasivos con los que hay que -> se han + participio pasado. se debe trabajar en singulares y plurales ha han
    //este condicional deberia ser mas corto en morfema como los dos siguientes o los dos siguientes deberian ser tan explicitos como este?
    if ($mArray[$ids] == 'V.XPPAPN') {
        $generoVP = $nextA['mf'] ?? 'm';
        // haSido habiendoSido traduccion perfecto pasivo participio
        $sFo = 'seHan'.ucfirst(procesarFraseConVerboEnParticipio($su, $generoVP)) ?? 'NeN';
    }

    //habria que mirar como aplicar esto mismo para los diferentes subcategorias XMPAPN $mArray[$ids] == 'V.XMP'
    if (str_starts_with($mArray[$ids], 'V.XMP')) {
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'habiendoSido'.ucfirst(procesarFraseConVerboEnParticipio($su, $generoVP)) ?? 'NeN';
    }

    //habria que mirar como aplicar esto mismo para los diferentes subcategorias XAPAPN $mArray[$ids] == 'V.XAP'
    //pienso que esta seria la forma para hacer subcategorias de esta clase de verbos str_starts_with
    if (str_starts_with($mArray[$ids], 'V.XAP')) {
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'habiendo'.ucfirst(procesarFraseConVerboEnParticipio($su, $generoVP)) ?? 'NeN';
    }

    //RA.ASN
    //$mArray[$ids] == 'RA.ASF'
    //cambiar el articulo en acusativo

    //$mArray[$ids+1] == 'N.ASF'
    //esto cambia el genero del articulo, solo si la siguienge palabra es nominativo
    if (in_array($mArray[$ids], array('RA.ASN', 'RA.ASF', 'RA.ASM', 'RA.NSM', 'RA.DSF', 'RA.NSF')) && isset($mArray[$ids+1]) && in_array($mArray[$ids+1], array('N.ASF', 'N.NSN', 'N.ASN', 'N.ASM', 'N.NSM', 'N.DSF', 'N.NSF'))) {
        //(genderForNoun(str_replace(' ', '', $sArray[$ids+1])) == 'f'?'la':'el')
        $sFo = definiteArticle(str_replace(' ', '', strip_tags($sArray[$ids+1])));
        //'<!--<s class="d-inline-block text-secondary">'.$su.'</s>--> ';
        $sFo = str_replace('l', '|', $sFo);
        //esto para saber si la palabra siguiente es femenina o masculina
        $nAmf = genderForNoun(str_replace(' ', '', $sArray[$ids+1]));
        $nextA = array('m' => $mArray[$ids+1], 'mf' => $nAmf, 'da' => definiteArticle(str_replace(' ', '', strip_tags($sArray[$ids+1]))), 'pd' => ($nAmf == 'm'?'este':'esta'));
    }

    //N.ASF tambien para los que no tienen articulo
    if (in_array($mArray[$ids], array('N.ASF'))) {
        $nAmf = genderForNoun(str_replace(' ', '', $sArray[$ids]));
        $nextA = array('m' => $mArray[$ids], 'mf' => $nAmf, 'da' => definiteArticle(str_replace(' ', '', strip_tags($sArray[$ids]))), 'pd' => ($nAmf == 'm'?'este':'esta'));
        //hay que mejorsr el codigo de definiteArticle $nAmf.$sArray[$ids] mujer
    }

    //genero del articulo de los adjetivos. codigo a mejorar. el siguiente tambien
    if (in_array($mArray[$ids], array('RA.ASN', 'RA.ASF', 'RA.ASM', 'RA.NSM', 'RA.DSF')) && isset($mArray[$ids+1]) && in_array($mArray[$ids+1], array('A.ASF', 'A.DSF'))) {
        //si el nominativo estuvo antes. habria que mirar si el nominativo esta despues. nextA significa genero para adjetivo posterior
        $sFo = (@$nextA['da'])."↑<!--<s>".$su."</s>-->";
    }

    //este codigo se podria mejorar junto con el condicional. esto es para modificar el genero de los adjetivos
    //'A.DSF'
    if (in_array($mArray[$ids], array('A.ASF', 'A.DSF', 'A.ASN'))) {
        $vN = isset($nextA['mf'])?$nextA['mf']:null;
        if ($vN != null) {
            $pMod = substr($su, 0, -1).($vN == 'f'?'4':'0');
        } else {
            $sliceP = array_slice($mArray, $ids+1);
            $mSR = str_replace('A.', 'N.', $mArray[$ids]);
            $iSearch = array_search($mSR, $sliceP);
            $gIS = @genderForNoun(str_replace(' ', '', $sArray[$ids+1+$iSearch]));
            $pMod = substr($su, 0, -1).($gIS == 'f'?'⁴':'º');
        }
        $sFo = "".$pMod."";
    }

    //pronombres demostrativos
    if ($mArray[$ids] == 'RD.ASF') {
        $sFo = (@$nextA['pd'])."↓<!--<s>".$su."</s>-->";
    }

    //a considerar. si encuentras una preposicion, al sustantivo anterior ya no le puedes agregar mas adjetivos. reinicia la variable nextA. sintagma
    if ($mArray[$ids] == 'P') {
        $nextA = array();
    }

    //cambiar el demostrativo basado en el genero anterior. mateo 9:26
    if ($mArray[$ids] == 'RD.ASF' && isset($mArray[$ids-1]) && $mArray[$ids-1] == 'N.ASF') {
        $mf = genderForNoun(str_replace(' ', '', $sArray[$ids-1]));
        $sFo = getDemostracionAdjective($mf, 's', 'ese').'<s class="d-inline-block text-secondary">'.$su.'</s> '/*.$mf.var_export($sArray[$ids-1], true)*/;
    }

    //eliminar a por preposicion de dativo
    //no seria mejor agregar en caso de que no haya preposicion? para eso se tendria que eliminar todas las incidencias de a en los dativos demostrativos
    //pienso que aqui tambien seria buena idea comenzar a mirar el genero de las morfologias para adaptarlo al genero del demostrativo
    if (in_array($mArray[$ids], array('RD.DSM', 'RP.DP', 'A.DSM', 'RD.DPM')) && isset($mArray[$ids-1]) && $mArray[$ids-1] == 'P') {
        //$mf = genderForNoun(str_replace(' ', '', $sArray[$ids-1]));
        //$sArray[$ids] = getDemostracionAdjective($mf, 's', 'ese').'<s class="d-inline-block text-secondary">'.$su.'</s> ';
        $sFo = str_replace('a', '×', $su);
    }

    //RD.DSF
    //agregar a por dativo singular
    //este no es singular RA.DPN. mirar si funciona alli o debe ponerse en otro condicional
    //faltaria el genero?
    /*if (in_array($mArray[$ids], array('RA.DSN', 'RD.DSF')) && (!isset($mArray[$ids-1]) || @$mArray[$ids-1] !== 'P')){*/
    if (in_array($mArray[$ids], array('RA.DSN', 'RD.DSF', 'A.DSN', 'A.DPN', 'RA.DPN', 'RA.DSF', 'N.DSF', 'N-DSF')) && (!isset($mArray[$ids-1]) || !in_array($mArray[$ids-1], array('P', 'A.DSN', 'A.DPN', 'RA.DPN')))) {
        //&& isset($mArray[$ids+1]) && $mArray[$ids+1] == 'N.DSM' esto lo encontre antes de la segunda parte de esta misma condicion, es decir del -1. pero no tenia sentido en algunas frases. habria que mirar si seria preciso usar esta parte del condicional dentro de este condicional
        //A.DSM
        if (isset($mArray[$ids-1]) && @$mArray[$ids-1] == 'A.DSM')
            $sFo = '<span class="text-warning bg-dark">δε</span>'.$su.' ';
        else
            $sFo = '<span class="text-warning bg-dark">α</span>'.$su.' '; //s
    }

    //agregar a por dativo plural
    //seria bueno solo agregar en español antes del articulo en plural la palabra a pero no se podria porque se omitiria el hecho de la singularidad o pluralidad de la palabra siguiente
    if (in_array($mArray[$ids], array('RA.DPF', 'RA.DPM')) && isset($mArray[$ids+1]) && in_array($mArray[$ids+1], array('N.DPF', 'N.DPM')) && (!isset($mArray[$ids-1]) || @$mArray[$ids-1] !== 'P')) {
        $sNext = $sArray[$ids+1];
        $dati = "a";
        if (substr($sNext, -2) === 'as')
            $sFo = '<span class="text-warning bg-dark">'.(@$dati).'</span>λας'."<!--<s>$su</s>-->"; //s
        else
            $sFo = '<span class="text-warning bg-dark">'.(@$dati).'</span>λος'."<!--<s>$su</s>-->"; //s
    }

    //V.XAPGPN son verbos que funcionan como adjetivos y no son conjugables (participio)
    if (in_array($mArray[$ids], array('RA.GPN')) && isset($mArray[$ids+1]) && in_array($mArray[$ids+1], array('V.XAPGPN')) && (!isset($mArray[$ids-1]) || @$mArray[$ids-1] == 'P')) {
        $sNext = $sArray[$ids+1];
        if (substr($sNext, -2) === 'as')
            $sFo = 'λΑς'."<!--<s>$su</s>-->"; //s
        else
            $sFo = 'λΟς'."<!--<s>$su</s>-->"; //s
    }

    //RA.APN N.APN
    //acusativo plural
    //RA.NPF podria aplicarse al nominativo plural tambien
    if (in_array($mArray[$ids], array('RA.APN', 'RA.NPF', 'RA.NPM')) && isset($mArray[$ids+1]) && in_array($mArray[$ids+1], array('N.APN', 'N.NPF', 'N.NPM'))) {
        $sNext = $sArray[$ids+1];
        if (substr($sNext, -2) === 'as')
            $sFo = 'lαs'."<!--<s>$su</s>-->"; //s
        else
            $sFo = 'lος'."<!--<s>$su</s>-->"; //s
    }

    //para por genitivo?. casi seguro que si. siendo asi, esto sobreescribiria lo anterior donde se sctiva la variable $fGA
    if (in_array($mArray[$ids], array('RA.GSN', 'C')) && isset($mArray[$ids+1]) && in_array($mArray[$ids+1], array('V.PAN', 'V.XAN', 'V.AAN')) && isset($mArray[$ids-1]) && !in_array($mArray[$ids-1], array('P'))) {
        $fGA = false;
        $sFo = '<span class="">para</span><s class=d-inline-block>'.$su.'</s> ';
    }

    //N.GSF y no es. lee bien. importante cambiar ya la estructura para que las etiquetas sean aplicadas al texto justo antes de imprimirlass y no antes de procesarlas y fGA false

    //pienso que se tenia que borrar poque la es añadido con el nuevo codigo
    if (in_array($mArray[$ids], array('N.GSF')) && isset($mArray[$ids-1]) && !in_array($mArray[$ids-1], array('RA.GSN', 'RA.GSF')) && false) {
        //$sArray[$ids] = '<span class="">para</span><s class=d-inline-block>'.$su.'</s> ';
        $sFo = str_replace('la', '×', $su);
    }

    if (in_array($mArray[$ids], array('A.ASN')) && isset($mArray[$ids-1]) && in_array($mArray[$ids-1], array('N.ASN'))) {
        //$sArray[$ids] = '<span class="">para</span><s class=d-inline-block>'.$su.'</s> ';
        //$sArray[$ids] = str_replace('la', '×', $su);

        //$palabra = "perro"; // Puedes cambiar esta palabra para probar

        // Convierte la palabra a minúsculas para que la comparación no distinga entre mayúsculas y minúsculas
        //echo 1;
        //$palabraMinusculas = strtolower($su);

        // Comprueba si la última letra es 'o'
        if (substr($su, -1) === 'o') {
            //echo "La palabra '" . $palabra . "' termina en 'o'.";
            //$sArray[$ids] = 'ttA';
            $sFo = substr($su, 0, -1).'æ';
        } else {
            //echo "La palabra '" . $palabra . "' NO termina en 'o'.";
            //$sArray[$ids] = 'ttO';
        }

    }

    //RA.NSM miremos a ver que pasa con los dativos V.PAPDSN
    //aqui como cuando hay que mirar genero de los morfemas para asociar, tambien hay que tener en cuenta en que tiempo esta el verbo pasado para cambiar esta por estaba. igual con el siguiente condicional
    if (in_array($mArray[$ids], array('V-PAP-NSM', 'V.PAPNSM', 'V.PAPDSN', 'V.PAPNSF')) && isset($mArray[$ids-1]) && in_array($mArray[$ids-1], array('RA.NSM', 'RA.DSN', 'RA.NSF'))) {
        $sFo = 'qυεεstα'.$su/*.'<sub>db</sub>'*/;
    }

    //V.PAPNPM
    if (in_array($mArray[$ids], array('V-PAP-NPM', 'V.PAPNPM')) && isset($mArray[$ids-1]) && in_array($mArray[$ids-1], array('RA.NPM'))) {
        $sFo = 'qυεεstαn'.$su/*.'<sub>db</sub>'*/;
    }

    //Antes se hizo parte, faltaria el dativo y lo siguiente despues de lo que sigue
    # los siguientes verbos pueden ir en acusativo nominativo y genitivo. Es decir de los que estan caminando (genitivo) o los que estan caminando (nominativo, acusativo)
    #  V.PAPASM R7 V.PAPNPF R8
    # para las palabras siguientes seria igual que lo anterior mas se. es decir corriendo + se. aunque en este caso no es necesario porque conjes lo trae de la tabla esp_verbos en esa forma
    #  V.PMP
    # y para los siguientes seria para despues + infinitivo y si esta en dativo a los que + despues de + infinitivo
    #  V.AAPNSF R6 V.AAPNSM R16
    //habria que mejorarlo igual para todas las subcategorias
    if ($mArray[$ids] == 'V.AAP') {
        $generoVP = $nextA['mf'] ?? 'm';
        // habiendo pedido
        $sFo = 'despuesDe'.ucfirst(procesarFraseConVerboEnParticipio($su, $generoVP)) ?? 'NeN';
    }

    //igual que al anterior pero en voz media
    if ($mArray[$ids] == 'V.AMP') {
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'habiendo'.ucfirst(procesarFraseConVerboEnParticipio($su, $generoVP)).'ParaSi' ?? 'NeN';
    }

    //igual que al anterior pero en voz pasiva
    if ($mArray[$ids] == 'V.APP') {
        $generoVP = $nextA['mf'] ?? 'm';
        $sFo = 'habiendoSido'.ucfirst(procesarFraseConVerboEnParticipio($su, $generoVP)) ?? 'NeN';
    }

    /*En español, podríamos intentar reflejar la diferencia aspectual de otras maneras, aunque no siempre se hace explícitamente:
        Aoristo Medio Infinitivo (como εὐαγγελιˊσασθαι): "anunciar buenas noticias para sí" (acción puntual).
        Presente Medio Infinitivo (como ἐργαˊζεσθαι): "estar trabajando para sí" (acción continua). Aquí, el uso de "estar + gerundio" intenta capturar la idea de la acción en curso.*/
    //php gerundio

    //para comprobar que se esta haciendo bien
    //$sArray[$ids] = $sArray[$ids].'<sub>'.$iArray[$ids].',</sub>';

    //este codigo debe activarse despues que se haga lo que se tiene que hacer
    //si se mete toLowerCamelCase que sea aqui para no tener que meter en cada condicional. habria que mirar que hacer con los html tags
    $rArray[$ids] = "<sp $iArray[$ids] class='".(@$gArray[$ids])."' morp='".$mArray[$ids]."'>"/*.$usuario['morp'].' '*/./*$sArray[$ids]*/$sFo."</sp> "/*.$mArray[$ids]*/;
}
echo '<tes class="text-secondary-emphasis">'.$res['verse'].' '.implode(' ', $rArray).'</tes>';
echo '</div>';
?>