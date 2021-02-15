<?php

class Uniqueslug
{



public function unique_slug($name, $table)
    {
        $slug = self::slugify($name);
        
        $titles = array();
        
        $query = get_sql("SELECT slug FROM $table WHERE slug RLIKE '(".$slug.")(-[0-9]+)?$'" , 'array');

        if(count($query) > 0)
        {
            foreach($query as $item)
            {
                $titles[] = $item["slug"];
            }
        }

        $total  = count($titles);
        $last   = end($titles);
        
        if($total == 0)
            return $slug;
                    
        elseif($total == 1)
        {

            $exists = $titles[0];
            
            $exists = str_replace($slug, "", $exists);
            

            if("" == trim($exists))
                return $slug."-1";
            
            else {

                $number = str_replace("-", "", $exists);
                
                $number++;
                
                return $slug."-".$number;                
            }
        }
        
        else {
            $exists = $last;
            
            $exists = str_replace($slug, "", $exists); 
            
            $number = str_replace("-", "", $exists);
            
            $number++;
            
            return $slug."-".$number;
        }
    }



    public static function slugify($string, $separator = '-')
    {

        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = [ '&' => 'and', "'" => ''];
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace('/[^a-z0-9]/u', $separator, $string);
        
        return preg_replace('/['.$separator.']+/u', $separator, $string);

    
    }

}