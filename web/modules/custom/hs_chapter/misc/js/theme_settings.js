var inp;
jQuery(document).ready(function() {

  console.log('hs_chapter/js/theme_settings.js loaded 0.0027');

  // colorwheel
  var f = jQuery.farbtastic('#picker');
  var p = jQuery('#picker').css('opacity', 1);
  var selected;
  jQuery('.colorwell')
    .each(function () { f.linkTo(this); jQuery(this).css('opacity', 1); })
    .focus(function() {
      if (selected) {
        jQuery(selected).css('opacity', 1).removeClass('colorwell-selected');
      }
      f.linkTo(this);
      p.css('opacity', 1);
      jQuery(selected = this).css('opacity', 1).addClass('colorwell-selected');
    });

  // google fonts select / autocomplete
  var currentFocus = "";
  var font_names = ["ABeeZee","Abel","Abhaya Libre","Abril Fatface","Aclonica","Acme","Actor","Adamina","Advent Pro","Aguafina Script","Akaya Kanadaka","Akaya Telivigala","Akronim","Aladin","Alata","Alatsi","Aldrich","Alef","Alegreya","Alegreya SC","Alegreya Sans","Alegreya Sans SC","Aleo","Alex Brush","Alfa Slab One","Alice","Alike","Alike Angular","Allan","Allerta","Allerta Stencil","Allison","Allura","Almarai","Almendra","Almendra Display","Almendra SC","Alumni Sans","Amarante","Amaranth","Amatic SC","Amethysta","Amiko","Amiri","Amita","Anaheim","Andada Pro","Andika","Andika New Basic","Angkor","Annie Use Your Telescope","Anonymous Pro","Antic","Antic Didone","Antic Slab","Anton","Antonio","Arapey","Arbutus","Arbutus Slab","Architects Daughter","Archivo","Archivo Black","Archivo Narrow","Are You Serious","Aref Ruqaa","Arima Madurai","Arimo","Arizonia","Armata","Arsenal","Artifika","Arvo","Arya","Asap","Asap Condensed","Asar","Asset","Assistant","Astloch","Asul","Athiti","Atkinson Hyperlegible","Atma","Atomic Age","Aubrey","Audiowide","Autour One","Average","Average Sans","Averia Gruesa Libre","Averia Libre","Averia Sans Libre","Averia Serif Libre","Azeret Mono","B612","B612 Mono","Bad Script","Bahiana","Bahianita","Bai Jamjuree","Bakbak One","Ballet","Baloo 2","Baloo Bhai 2","Baloo Bhaijaan 2","Baloo Bhaina 2","Baloo Chettan 2","Baloo Da 2","Baloo Paaji 2","Baloo Tamma 2","Baloo Tammudu 2","Baloo Thambi 2","Balsamiq Sans","Balthazar","Bangers","Barlow","Barlow Condensed","Barlow Semi Condensed","Barriecito","Barrio","Basic","Baskervville","Battambang","Baumans","Bayon","Be Vietnam Pro","Bebas Neue","Belgrano","Bellefair","Belleza","Bellota","Bellota Text","BenchNine","Benne","Bentham","Berkshire Swash","Besley","Beth Ellen","Bevan","BhuTuka Expanded One","Big Shoulders Display","Big Shoulders Inline Display","Big Shoulders Inline Text","Big Shoulders Stencil Display","Big Shoulders Stencil Text","Big Shoulders Text","Bigelow Rules","Bigshot One","Bilbo","Bilbo Swash Caps","BioRhyme","BioRhyme Expanded","Birthstone","Birthstone Bounce","Biryani","Bitter","Black And White Picture","Black Han Sans","Black Ops One","Blinker","Bodoni Moda","Bokor","Bona Nova","Bonbon","Bonheur Royale","Boogaloo","Bowlby One","Bowlby One SC","Brawler","Bree Serif","Brygada 1918","Bubblegum Sans","Bubbler One","Buda","Buenard","Bungee","Bungee Hairline","Bungee Inline","Bungee Outline","Bungee Shade","Butcherman","Butterfly Kids","Cabin","Cabin Condensed","Cabin Sketch","Caesar Dressing","Cagliostro","Cairo","Caladea","Calistoga","Calligraffitti","Cambay","Cambo","Candal","Cantarell","Cantata One","Cantora One","Capriola","Caramel","Carattere","Cardo","Carme","Carrois Gothic","Carrois Gothic SC","Carter One","Castoro","Catamaran","Caudex","Caveat","Caveat Brush","Cedarville Cursive","Ceviche One","Chakra Petch","Changa","Changa One","Chango","Charm","Charmonman","Chathura","Chau Philomene One","Chela One","Chelsea Market","Chenla","Cherish","Cherry Cream Soda","Cherry Swash","Chewy","Chicle","Chilanka","Chivo","Chonburi","Cinzel","Cinzel Decorative","Clicker Script","Coda","Coda Caption","Codystar","Coiny","Combo","Comfortaa","Comforter","Comforter Brush","Comic Neue","Coming Soon","Commissioner","Concert One","Condiment","Content","Contrail One","Convergence","Cookie","Copse","Corben","Corinthia","Cormorant","Cormorant Garamond","Cormorant Infant","Cormorant SC","Cormorant Unicase","Cormorant Upright","Courgette","Courier Prime","Cousine","Coustard","Covered By Your Grace","Crafty Girls","Creepster","Crete Round","Crimson Pro","Croissant One","Crushed","Cuprum","Cute Font","Cutive","Cutive Mono","DM Mono","DM Sans","DM Serif Display","DM Serif Text","Damion","Dancing Script","Dangrek","Darker Grotesque","David Libre","Dawning of a New Day","Days One","Dekko","Dela Gothic One","Delius","Delius Swash Caps","Delius Unicase","Della Respira","Denk One","Devonshire","Dhurjati","Didact Gothic","Diplomata","Diplomata SC","Do Hyeon","Dokdo","Domine","Donegal One","Dongle","Doppio One","Dorsa","Dosis","DotGothic16","Dr Sugiyama","Duru Sans","Dynalight","EB Garamond","Eagle Lake","East Sea Dokdo","Eater","Economica","Eczar","El Messiri","Electrolize","Elsie","Elsie Swash Caps","Emblema One","Emilys Candy","Encode Sans","Encode Sans Condensed","Encode Sans Expanded","Encode Sans SC","Encode Sans Semi Condensed","Encode Sans Semi Expanded","Engagement","Englebert","Enriqueta","Ephesis","Epilogue","Erica One","Esteban","Estonia","Euphoria Script","Ewert","Exo","Exo 2","Expletus Sans","Explora","Fahkwang","Fanwood Text","Farro","Farsan","Fascinate","Fascinate Inline","Faster One","Fasthand","Fauna One","Faustina","Federant","Federo","Felipa","Fenix","Festive","Finger Paint","Fira Code","Fira Mono","Fira Sans","Fira Sans Condensed","Fira Sans Extra Condensed","Fjalla One","Fjord One","Flamenco","Flavors","Fleur De Leah","Flow Block","Flow Circular","Flow Rounded","Fondamento","Fontdiner Swanky","Forum","Francois One","Frank Ruhl Libre","Fraunces","Freckle Face","Fredericka the Great","Fredoka","Fredoka One","Freehand","Fresca","Frijole","Fruktur","Fugaz One","Fuggles","Fuzzy Bubbles","GFS Didot","GFS Neohellenic","Gabriela","Gaegu","Gafata","Galada","Galdeano","Galindo","Gamja Flower","Gayathri","Gelasio","Gemunu Libre","Genos","Gentium Basic","Gentium Book Basic","Geo","Georama","Geostar","Geostar Fill","Germania One","Gideon Roman","Gidugu","Gilda Display","Girassol","Give You Glory","Glass Antiqua","Glegoo","Gloria Hallelujah","Glory","Gluten","Goblin One","Gochi Hand","Goldman","Gorditas","Gothic A1","Gotu","Goudy Bookletter 1911","Gowun Batang","Gowun Dodum","Graduate","Grand Hotel","Grandstander","Gravitas One","Great Vibes","Grechen Fuemen","Grenze","Grenze Gotisch","Grey Qo","Griffy","Gruppo","Gudea","Gugi","Gupter","Gurajada","Gwendolyn","Habibi","Hachi Maru Pop","Hahmlet","Halant","Hammersmith One","Hanalei","Hanalei Fill","Handlee","Hanuman","Happy Monkey","Harmattan","Headland One","Heebo","Henny Penny","Hepta Slab","Herr Von Muellerhoff","Hi Melody","Hina Mincho","Hind","Hind Guntur","Hind Madurai","Hind Siliguri","Hind Vadodara","Holtwood One SC","Homemade Apple","Homenaje","Hubballi","Hurricane","IBM Plex Mono","IBM Plex Sans","IBM Plex Sans Arabic","IBM Plex Sans Condensed","IBM Plex Sans Devanagari","IBM Plex Sans Hebrew","IBM Plex Sans KR","IBM Plex Sans Thai","IBM Plex Sans Thai Looped","IBM Plex Serif","IM Fell DW Pica","IM Fell DW Pica SC","IM Fell Double Pica","IM Fell Double Pica SC","IM Fell English","IM Fell English SC","IM Fell French Canon","IM Fell French Canon SC","IM Fell Great Primer","IM Fell Great Primer SC","Ibarra Real Nova","Iceberg","Iceland","Imbue","Imperial Script","Imprima","Inconsolata","Inder","Indie Flower","Inika","Inknut Antiqua","Inria Sans","Inria Serif","Inspiration","Inter","Irish Grover","Island Moments","Istok Web","Italiana","Italianno","Itim","Jacques Francois","Jacques Francois Shadow","Jaldi","JetBrains Mono","Jim Nightshade","Jockey One","Jolly Lodger","Jomhuria","Jomolhari","Josefin Sans","Josefin Slab","Jost","Joti One","Jua","Judson","Julee","Julius Sans One","Junge","Jura","Just Another Hand","Just Me Again Down Here","K2D","Kadwa","Kaisei Decol","Kaisei HarunoUmi","Kaisei Opti","Kaisei Tokumin","Kalam","Kameron","Kanit","Kantumruy","Karantina","Karla","Karma","Katibeh","Kaushan Script","Kavivanar","Kavoon","Kdam Thmor","Keania One","Kelly Slab","Kenia","Khand","Khmer","Khula","Kings","Kirang Haerang","Kite One","Kiwi Maru","Klee One","Knewave","KoHo","Kodchasan","Koh Santepheap","Kolker Brush","Kosugi","Kosugi Maru","Kotta One","Koulen","Kranky","Kreon","Kristi","Krona One","Krub","Kufam","Kulim Park","Kumar One","Kumar One Outline","Kumbh Sans","Kurale","La Belle Aurore","Lacquer","Laila","Lakki Reddy","Lalezar","Lancelot","Langar","Lateef","Lato","League Gothic","League Script","League Spartan","Leckerli One","Ledger","Lekton","Lemon","Lemonada","Lexend","Lexend Deca","Lexend Exa","Lexend Giga","Lexend Mega","Lexend Peta","Lexend Tera","Lexend Zetta","Libre Barcode 128","Libre Barcode 128 Text","Libre Barcode 39","Libre Barcode 39 Extended","Libre Barcode 39 Extended Text","Libre Barcode 39 Text","Libre Barcode EAN13 Text","Libre Baskerville","Libre Caslon Display","Libre Caslon Text","Libre Franklin","Licorice","Life Savers","Lilita One","Lily Script One","Limelight","Linden Hill","Literata","Liu Jian Mao Cao","Livvic","Lobster","Lobster Two","Londrina Outline","Londrina Shadow","Londrina Sketch","Londrina Solid","Long Cang","Lora","Love Light","Love Ya Like A Sister","Loved by the King","Lovers Quarrel","Luckiest Guy","Lusitana","Lustria","Luxurious Roman","Luxurious Script","M PLUS 1","M PLUS 1 Code","M PLUS 1p","M PLUS 2","M PLUS Code Latin","M PLUS Rounded 1c","Ma Shan Zheng","Macondo","Macondo Swash Caps","Mada","Magra","Maiden Orange","Maitree","Major Mono Display","Mako","Mali","Mallanna","Mandali","Manjari","Manrope","Mansalva","Manuale","Marcellus","Marcellus SC","Marck Script","Margarine","Markazi Text","Marko One","Marmelad","Martel","Martel Sans","Marvel","Mate","Mate SC","Maven Pro","McLaren","Mea Culpa","Meddon","MedievalSharp","Medula One","Meera Inimai","Megrim","Meie Script","Meow Script","Merienda","Merienda One","Merriweather","Merriweather Sans","Metal","Metal Mania","Metamorphous","Metrophobic","Michroma","Milonga","Miltonian","Miltonian Tattoo","Mina","Miniver","Miriam Libre","Mirza","Miss Fajardose","Mitr","Mochiy Pop One","Mochiy Pop P One","Modak","Modern Antiqua","Mogra","Mohave","Molengo","Molle","Monda","Monofett","Monoton","Monsieur La Doulaise","Montaga","Montagu Slab","MonteCarlo","Montez","Montserrat","Montserrat Alternates","Montserrat Subrayada","Moo Lah Lah","Moon Dance","Moul","Moulpali","Mountains of Christmas","Mouse Memoirs","Mr Bedfort","Mr Dafoe","Mr De Haviland","Mrs Saint Delafield","Mrs Sheppards","Mukta","Mukta Mahee","Mukta Malar","Mukta Vaani","Mulish","Murecho","MuseoModerno","Mystery Quest","NTR","Nanum Brush Script","Nanum Gothic","Nanum Gothic Coding","Nanum Myeongjo","Nanum Pen Script","Neonderthaw","Nerko One","Neucha","Neuton","New Rocker","New Tegomin","News Cycle","Newsreader","Niconne","Niramit","Nixie One","Nobile","Nokora","Norican","Nosifer","Notable","Nothing You Could Do","Noticia Text","Noto Kufi Arabic","Noto Music","Noto Naskh Arabic","Noto Nastaliq Urdu","Noto Rashi Hebrew","Noto Sans","Noto Sans Adlam","Noto Sans Adlam Unjoined","Noto Sans Anatolian Hieroglyphs","Noto Sans Arabic","Noto Sans Armenian","Noto Sans Avestan","Noto Sans Balinese","Noto Sans Bamum","Noto Sans Bassa Vah","Noto Sans Batak","Noto Sans Bengali","Noto Sans Bhaiksuki","Noto Sans Brahmi","Noto Sans Buginese","Noto Sans Buhid","Noto Sans Canadian Aboriginal","Noto Sans Carian","Noto Sans Caucasian Albanian","Noto Sans Chakma","Noto Sans Cham","Noto Sans Cherokee","Noto Sans Coptic","Noto Sans Cuneiform","Noto Sans Cypriot","Noto Sans Deseret","Noto Sans Devanagari","Noto Sans Display","Noto Sans Duployan","Noto Sans Egyptian Hieroglyphs","Noto Sans Elbasan","Noto Sans Elymaic","Noto Sans Georgian","Noto Sans Glagolitic","Noto Sans Gothic","Noto Sans Grantha","Noto Sans Gujarati","Noto Sans Gunjala Gondi","Noto Sans Gurmukhi","Noto Sans HK","Noto Sans Hanifi Rohingya","Noto Sans Hanunoo","Noto Sans Hatran","Noto Sans Hebrew","Noto Sans Imperial Aramaic","Noto Sans Indic Siyaq Numbers","Noto Sans Inscriptional Pahlavi","Noto Sans Inscriptional Parthian","Noto Sans JP","Noto Sans Javanese","Noto Sans KR","Noto Sans Kaithi","Noto Sans Kannada","Noto Sans Kayah Li","Noto Sans Kharoshthi","Noto Sans Khmer","Noto Sans Khojki","Noto Sans Khudawadi","Noto Sans Lao","Noto Sans Lepcha","Noto Sans Limbu","Noto Sans Linear A","Noto Sans Linear B","Noto Sans Lisu","Noto Sans Lycian","Noto Sans Lydian","Noto Sans Mahajani","Noto Sans Malayalam","Noto Sans Mandaic","Noto Sans Manichaean","Noto Sans Marchen","Noto Sans Masaram Gondi","Noto Sans Math","Noto Sans Mayan Numerals","Noto Sans Medefaidrin","Noto Sans Meetei Mayek","Noto Sans Meroitic","Noto Sans Miao","Noto Sans Modi","Noto Sans Mongolian","Noto Sans Mono","Noto Sans Mro","Noto Sans Multani","Noto Sans Myanmar","Noto Sans N Ko","Noto Sans Nabataean","Noto Sans New Tai Lue","Noto Sans Newa","Noto Sans Nushu","Noto Sans Ogham","Noto Sans Ol Chiki","Noto Sans Old Hungarian","Noto Sans Old Italic","Noto Sans Old North Arabian","Noto Sans Old Permic","Noto Sans Old Persian","Noto Sans Old Sogdian","Noto Sans Old South Arabian","Noto Sans Old Turkic","Noto Sans Oriya","Noto Sans Osage","Noto Sans Osmanya","Noto Sans Pahawh Hmong","Noto Sans Palmyrene","Noto Sans Pau Cin Hau","Noto Sans Phags Pa","Noto Sans Phoenician","Noto Sans Psalter Pahlavi","Noto Sans Rejang","Noto Sans Runic","Noto Sans SC","Noto Sans Samaritan","Noto Sans Saurashtra","Noto Sans Sharada","Noto Sans Shavian","Noto Sans Siddham","Noto Sans Sinhala","Noto Sans Sogdian","Noto Sans Sora Sompeng","Noto Sans Soyombo","Noto Sans Sundanese","Noto Sans Syloti Nagri","Noto Sans Symbols","Noto Sans Symbols 2","Noto Sans Syriac","Noto Sans TC","Noto Sans Tagalog","Noto Sans Tagbanwa","Noto Sans Tai Le","Noto Sans Tai Tham","Noto Sans Tai Viet","Noto Sans Takri","Noto Sans Tamil","Noto Sans Tamil Supplement","Noto Sans Telugu","Noto Sans Thaana","Noto Sans Thai","Noto Sans Thai Looped","Noto Sans Tifinagh","Noto Sans Tirhuta","Noto Sans Ugaritic","Noto Sans Vai","Noto Sans Wancho","Noto Sans Warang Citi","Noto Sans Yi","Noto Sans Zanabazar Square","Noto Serif","Noto Serif Ahom","Noto Serif Armenian","Noto Serif Balinese","Noto Serif Bengali","Noto Serif Devanagari","Noto Serif Display","Noto Serif Dogra","Noto Serif Ethiopic","Noto Serif Georgian","Noto Serif Grantha","Noto Serif Gujarati","Noto Serif Gurmukhi","Noto Serif Hebrew","Noto Serif JP","Noto Serif KR","Noto Serif Kannada","Noto Serif Khmer","Noto Serif Lao","Noto Serif Malayalam","Noto Serif Myanmar","Noto Serif Nyiakeng Puachue Hmong","Noto Serif SC","Noto Serif Sinhala","Noto Serif TC","Noto Serif Tamil","Noto Serif Tangut","Noto Serif Telugu","Noto Serif Thai","Noto Serif Tibetan","Noto Serif Yezidi","Noto Traditional Nushu","Nova Cut","Nova Flat","Nova Mono","Nova Oval","Nova Round","Nova Script","Nova Slim","Nova Square","Numans","Nunito","Nunito Sans","Odibee Sans","Odor Mean Chey","Offside","Oi","Old Standard TT","Oldenburg","Ole","Oleo Script","Oleo Script Swash Caps","Oooh Baby","Open Sans","Oranienbaum","Orbitron","Oregano","Orelega One","Orienta","Original Surfer","Oswald","Otomanopee One","Outfit","Over the Rainbow","Overlock","Overlock SC","Overpass","Overpass Mono","Ovo","Oxanium","Oxygen","Oxygen Mono","PT Mono","PT Sans","PT Sans Caption","PT Sans Narrow","PT Serif","PT Serif Caption","Pacifico","Padauk","Palanquin","Palanquin Dark","Palette Mosaic","Pangolin","Paprika","Parisienne","Passero One","Passion One","Passions Conflict","Pathway Gothic One","Patrick Hand","Patrick Hand SC","Pattaya","Patua One","Pavanam","Paytone One","Peddana","Peralta","Permanent Marker","Petemoss","Petit Formal Script","Petrona","Philosopher","Piazzolla","Piedra","Pinyon Script","Pirata One","Plaster","Play","Playball","Playfair Display","Playfair Display SC","Podkova","Poiret One","Poller One","Poly","Pompiere","Pontano Sans","Poor Story","Poppins","Port Lligat Sans","Port Lligat Slab","Potta One","Pragati Narrow","Praise","Prata","Preahvihear","Press Start 2P","Pridi","Princess Sofia","Prociono","Prompt","Prosto One","Proza Libre","Public Sans","Puppies Play","Puritan","Purple Purse","Qahiri","Quando","Quantico","Quattrocento","Quattrocento Sans","Questrial","Quicksand","Quintessential","Qwigley","Qwitcher Grypen","Racing Sans One","Radley","Rajdhani","Rakkas","Raleway","Raleway Dots","Ramabhadra","Ramaraja","Rambla","Rammetto One","Rampart One","Ranchers","Rancho","Ranga","Rasa","Rationale","Ravi Prakash","Readex Pro","Recursive","Red Hat Display","Red Hat Mono","Red Hat Text","Red Rose","Redacted","Redacted Script","Redressed","Reem Kufi","Reenie Beanie","Reggae One","Revalia","Rhodium Libre","Ribeye","Ribeye Marrow","Righteous","Risque","Road Rage","Roboto","Roboto Condensed","Roboto Mono","Roboto Serif","Roboto Slab","Rochester","Rock 3D","Rock Salt","RocknRoll One","Rokkitt","Romanesco","Ropa Sans","Rosario","Rosarivo","Rouge Script","Rowdies","Rozha One","Rubik","Rubik Beastly","Rubik Mono One","Ruda","Rufina","Ruge Boogie","Ruluko","Rum Raisin","Ruslan Display","Russo One","Ruthie","Rye","STIX Two Text","Sacramento","Sahitya","Sail","Saira","Saira Condensed","Saira Extra Condensed","Saira Semi Condensed","Saira Stencil One","Salsa","Sanchez","Sancreek","Sansita","Sansita Swashed","Sarabun","Sarala","Sarina","Sarpanch","Sassy Frass","Satisfy","Sawarabi Gothic","Sawarabi Mincho","Scada","Scheherazade New","Schoolbell","Scope One","Seaweed Script","Secular One","Sedgwick Ave","Sedgwick Ave Display","Sen","Sevillana","Seymour One","Shadows Into Light","Shadows Into Light Two","Shalimar","Shanti","Share","Share Tech","Share Tech Mono","Shippori Antique","Shippori Antique B1","Shippori Mincho","Shippori Mincho B1","Shizuru","Shojumaru","Short Stack","Shrikhand","Siemreap","Sigmar One","Signika","Signika Negative","Simonetta","Single Day","Sintony","Sirin Stencil","Six Caps","Skranji","Slabo 13px","Slabo 27px","Slackey","Smokum","Smooch","Smooch Sans","Smythe","Sniglet","Snippet","Snowburst One","Sofadi One","Sofia","Solway","Song Myung","Sonsie One","Sora","Sorts Mill Goudy","Source Code Pro","Source Sans 3","Source Sans Pro","Source Serif 4","Source Serif Pro","Space Grotesk","Space Mono","Spartan","Special Elite","Spectral","Spectral SC","Spicy Rice","Spinnaker","Spirax","Spline Sans","Squada One","Sree Krushnadevaraya","Sriracha","Srisakdi","Staatliches","Stalemate","Stalinist One","Stardos Stencil","Stick","Stick No Bills","Stint Ultra Condensed","Stint Ultra Expanded","Stoke","Strait","Style Script","Stylish","Sue Ellen Francisco","Suez One","Sulphur Point","Sumana","Sunflower","Sunshiney","Supermercado One","Sura","Suranna","Suravaram","Suwannaphum","Swanky and Moo Moo","Syncopate","Syne","Syne Mono","Syne Tactile","Tajawal","Tangerine","Taprom","Tauri","Taviraj","Teko","Telex","Tenali Ramakrishna","Tenor Sans","Text Me One","Texturina","Thasadith","The Girl Next Door","The Nautigal","Tienne","Tillana","Timmana","Tinos","Titan One","Titillium Web","Tomorrow","Tourney","Trade Winds","Train One","Trirong","Trispace","Trocchi","Trochut","Truculenta","Trykker","Tulpen One","Turret Road","Twinkle Star","Ubuntu","Ubuntu Condensed","Ubuntu Mono","Uchen","Ultra","Uncial Antiqua","Underdog","Unica One","UnifrakturCook","UnifrakturMaguntia","Unkempt","Unlock","Unna","Urbanist","VT323","Vampiro One","Varela","Varela Round","Varta","Vast Shadow","Vesper Libre","Viaoda Libre","Vibes","Vibur","Vidaloka","Viga","Voces","Volkhov","Vollkorn","Vollkorn SC","Voltaire","Vujahday Script","Waiting for the Sunrise","Wallpoet","Walter Turncoat","Warnes","Waterfall","Wellfleet","Wendy One","WindSong","Wire One","Work Sans","Xanh Mono","Yaldevi","Yanone Kaffeesatz","Yantramanav","Yatra One","Yellowtail","Yeon Sung","Yeseva One","Yesteryear","Yomogi","Yrsa","Yuji Boku","Yuji Hentaigana Akari","Yuji Hentaigana Akebono","Yuji Mai","Yuji Syuku","Yusei Magic","ZCOOL KuaiLe","ZCOOL QingKe HuangYou","ZCOOL XiaoWei","Zen Antique","Zen Antique Soft","Zen Dots","Zen Kaku Gothic Antique","Zen Kaku Gothic New","Zen Kurenaido","Zen Loop","Zen Maru Gothic","Zen Old Mincho","Zen Tokyo Zoo","Zeyada","Zhi Mang Xing","Zilla Slab","Zilla Slab Highlight"];
  jQuery('.font-select').keydown(function(e) {
    console.log('keydown: ' + e.keyCode);
    console.log(jQuery(this).val());
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) { //up
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
      }
    }
  });
  jQuery('.font-select').keyup(function(e) {
    console.log('keyup: ' + e.keyCode);
    console.log(jQuery(this).val());
    var a, b, i, val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) { return false;}
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    inp = this;
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < font_names.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (font_names[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        console.log(font_names[i]);
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML = "<strong>" + font_names[i].substr(0, val.length) + "</strong>";
        b.innerHTML += font_names[i].substr(val.length);
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + font_names[i] + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function(e) {
          /*insert the value for the autocomplete text field:*/
          inp.value = this.getElementsByTagName("input")[0].value;
          /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
          closeAllLists();
        });
        a.appendChild(b);
      }
    }


  });

});

function closeAllLists(elmnt) {
  /*close all autocomplete lists in the document,
  except the one passed as an argument:*/
  var x = document.getElementsByClassName("autocomplete-items");
  for (var i = 0; i < x.length; i++) {
    if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
function addActive(x) {
  /*a function to classify an item as "active":*/
  if (!x) return false;
  /*start by removing the "active" class on all items:*/
  removeActive(x);
  if (currentFocus >= x.length) currentFocus = 0;
  if (currentFocus < 0) currentFocus = (x.length - 1);
  /*add class "autocomplete-active":*/
  x[currentFocus].classList.add("autocomplete-active");
}
function removeActive(x) {
  /*a function to remove the "active" class from all autocomplete items:*/
  for (var i = 0; i < x.length; i++) {
    x[i].classList.remove("autocomplete-active");
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
  closeAllLists(e.target);
});
