<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 18.11.13
 * Time: 15:29
 * To change this template use File | Settings | File Templates.
 */

class Translator {

    private $languages;
    private $matching = array();

    function __construct($languages)
    {
           $this->languages = $languages;
    }


    /*
     * @returns array()
     */
    public function findString($string = '',$language,$controllers = array())
    {


            $dir = new RecursiveDirectoryIterator(DIR_LANGUAGE.$this->languages[$language]['directory'].'/');
            $iterator = new RecursiveIteratorIterator($dir);
            $files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

            $this->matching[$this->languages[$language]['code']] = array();


            /*
             * przeszukujemy wszystkie pliki w danym podkatalogu jezykowym
             */

            foreach($files as $name => $file)
            {


                $shortName =str_ireplace(DIR_LANGUAGE.$this->languages[$language]['directory'].'/','',$name);

                $handle = fopen($name, 'rw');

                if(strpos($shortName,'/')!==false AND !in_array($shortName,$controllers))
                {

                    continue;
                }

                while (($buffer = fgets($handle)) !== false) {


                    if($shortName == "common/footer.php")
                    {
                        // var_dump($string);
                      //   var_dump($buffer);
                    }

                    if (preg_match('/^(.*)=+ *+(\'|")+ *+'.$string.'+ *+(\'|")+ *+;/',$buffer) != false) {



                        $this->matching[$this->languages[$language]['code']][] = array(
                            'name' => $name,
                            'buffer' => $buffer,
                        );

                    }
                }

                fclose($handle);
            }

        return $this->matching[$this->languages[$language]['code']];


    }

    public function save($stara_fraza,$nowa_fraza,$pliki)
    {
        $t = function($buffer)use($stara_fraza,$nowa_fraza){
            return  str_ireplace($stara_fraza,$nowa_fraza,$buffer);
        };

        ;

        if(is_array($pliki))
        {
            foreach($pliki as $plik)
            {

                $whole_file = file_get_contents($pliki);

                $handle = fopen($pliki, 'rw');

                while (($buffer = fgets($handle)) !== false) {

                    if(preg_match('/^(.*)=+ *+(\'|")+ *+'.$stara_fraza.'+ *+(\'|")+ *+;/',$buffer)!=false)
                    {
                        $new = preg_replace('/^(.*)=+ *+(\'|")+ *+'.$stara_fraza.'+ *+(\'|")+ *+;/',$t($buffer),$buffer);


                        $whole_file = str_ireplace($buffer,$new,$whole_file);

                        file_put_contents($pliki,$whole_file);
                    }

                }
            }
        }
        else
        {

            $whole_file = file_get_contents($pliki);

                $handle = fopen($pliki, 'rw');

                while (($buffer = fgets($handle)) !== false) {

                    if(preg_match('/^(.*)=+ *+(\'|")+ *+'.$stara_fraza.'+ *+(\'|")+ *+;/',$buffer)!=false)
                    {

                        $new = preg_replace('/^(.*)=+ *+(\'|")+ *+'.$stara_fraza.'+ *+(\'|")+ *+;/',$t($buffer),$buffer);

                        $whole_file = str_ireplace($buffer,$new,$whole_file);

                        file_put_contents($pliki,$whole_file);
                    }

                }

        }

    }
}