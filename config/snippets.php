<?
  #replacements 
  $snippets = array(
                '_w/o_'              => ROOT_URL.DATA_DIR.'/', 
                '%w/o%'              => ROOT_URL.DATA_DIR.'/',
                '_site_'             => ROOT_URL.'index.php',
                '%site%'             => ROOT_URL.'index.php',
                '_root_'             => ROOT_URL,
                '%root%'             => ROOT_URL, 
                '_static_'           => ROOT_URL.'static/',
                '%static%'           => ROOT_URL.'static/',
                "_ghImage_"          =>  
                                '<img  src="http://github.com/favicon.png" alt="" width="16" height="16"/>',
                "_ccImage_"          => 
                                '<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/de/"><img
                                    alt="Creative Commons License"
                                    src="http://i.creativecommons.org/l/by-nc-sa/3.0/de/88x31.png" /></a>',
                "_gradle_"         => '<a href="http://www.gradle.org">Gradle</a>',
                "/graph" => '/></div>',
                'dmath/'  => "<div class='math math-definition'>[mathdef] <p
                        class='math-inner'>\n amath",
                "/math" => "\nendamath\n</p></div>",
                'math/'  => "<div class='math math-block'> <p
                        class='math-inner'> \namath",
                "graph/"  => "<div class='graph'> <embed ",
                "&&&"     => '<span style="font-family: cursive;">&amp;</span>'
    );
?>
