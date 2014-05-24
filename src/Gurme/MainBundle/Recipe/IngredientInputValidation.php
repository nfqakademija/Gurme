<?php

namespace Gurme\MainBundle\Recipe;

use Doctrine\ORM\EntityManager;
use Gurme\MainBundle\Entity\Unit;

class IngredientInputValidation
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param $em EntityManager
     */
    public function __construct($em) {
        $this->em = $em;
        $this->repository = $em->getRepository('GurmeMainBundle:Unit');
    }

    /**
     * Function to validate ingredient input for database
     *
     * @param $contents
     * @return array
     */
    public function validate($contents)
    {
        $result = true;
        $ing = array();
        $searchFor = '';

        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if(preg_match_all($pattern, $contents, $matches)){
            //exit(var_dump(trim($matches[0])));
            $lines = $matches[0];
            $i=0;
            foreach ($lines as $line) {

                if (trim($line) == '') { continue; }

                $ing[$i]['valid']   = 'remove';
                $ing[$i]['amount']  = '';

                // Find any notes within brackets. e.g. (4 ponds)
                $pattern = '/\((.+)\)/';
                $matches = [];
                if(preg_match_all($pattern, $line, $matches)){
                    $ing[$i]['notes'] = $matches[0][0];
                    $line = preg_replace($pattern, '', $line);
                }

                $line = ' ' . $line;
                list($unitMatched,$ing[$i]['unit'],$ing[$i]['unitObj']) = $this->findUnit($line);

                // Get amount and ingredient data from line
                if ($unitMatched) {
                    $matches = [];
                    preg_match('/^\s*([. 0-9\/]+) '.$ing[$i]['unit'].'/', $line, $matches);
                    if (isset($matches[1])) {
                        $ing[$i]['amount'] = $matches[1];
                    }
                    $pattern = '/^.*'.$ing[$i]['unit'].'\s*((.*),\s*(.*)|(.*))/';
                    $matches = [];
                    preg_match($pattern, $line, $matches);
                    if (isset($matches[3]) && $matches[3]!='')
                    {
                        $ing[$i]['ingredient'] = $matches[2];
                        $ing[$i]['notes'] = (isset($ing[$i]['notes']) ?
                            $matches[3] . ' ' . $ing[$i]['notes'] : $matches[3]);
                    }
                    else
                    {
                        $ing[$i]['ingredient'] = $matches[1];
                    }
                } else {
                    $matches = [];
                    if(preg_match('/^\s*([. 0-9\/]*)((.*),\s(.*)|(.*))/', $line, $matches)) {
                        if (isset($matches[3])&&($matches[3]=='')&&($matches[3]=='')) {
                            $ing[$i]['amount'] = $matches[1];
                            $ing[$i]['ingredient'] = $matches[2];
                        } else {
                            $ing[$i]['amount'] = $matches[1];
                            $ing[$i]['ingredient'] = $matches[3];
                            $ing[$i]['notes'] = (isset($ing[$i]['notes']) ?
                                $matches[4] . ' ' . $ing[$i]['notes'] : $matches[4]);
                        }
                        if(trim($ing[$i]['amount']) != '') {
                            $ing[$i]['unit'] = 'units';
                            $ing[$i]['unitObj'] = $this->em->getRepository('GurmeMainBundle:Unit')
                                ->findOneBy(array('main' => 'unit'));
                        } else $ing[$i]['valid'] = 'ok';
                    }
                }

                if (!is_null($ing[$i]['ingredient'])) trim($ing[$i]['ingredient']);

                list($ing[$i]['amount'],$ing[$i]['valid']) = $this->convertToMetric($ing[$i]['amount'],$ing[$i]['valid']);

                $result = ($ing[$i]['valid']=='remove') ? false : $result;

                $i++;
            }

        }
        else $result = false;

        return array('status' => $result, 'ingredients' => $ing);
    }

    private function findUnit($line)
    {
        $name = null;
        $object = null;
        $units = $this->repository->findAll();
        $unitMatched = false;
        /** @var Unit $unit */
        foreach($units as $unit){
            if (null !== $unit->getMain()) {
                $pattern = (null !== $unit->getPlural()) ? '('.$unit->getMain().'|'.$unit->getPlural().')' : $unit->getMain() ;
                $pattern = '/\s('.$pattern.'s?)\s/';
                if (preg_match($pattern, $line, $matches)) {
                    //exit($matches[0]);
                    $name = $matches[1];
                    $object = $unit;
                    $unitMatched = true;
                }
            }
        }

        return array($unitMatched,$name,$object);
    }

    private function convertToMetric($amount,$valid)
    {
        $amount = trim($amount);
        if ($amount != '') {
            $pattern = '/((([0-9]*)\s+([0-9]*)\/([0-9]*))|([0-9]*\.[0-9]*)|(([0-9]*)\/([0-9]*))|([0-9]*))/';
            if (preg_match($pattern,$amount,$matches)) {
                $valid = 'ok';
                for ($i=count($matches);$i > 0;$i--) {
                    if (isset($matches[$i]) && ($matches[$i]!='')) {
                        switch ($i)
                        {
                            case 10:
                                break 2; // sveikas skaičius pvz 2,8,40 (rodo kaip STRING)
                            case 9:
                                $amount = $matches[8] / $matches[9] ; // pvz "1/3" = 0.3(3)
                                break 2;
                            case 6:
                                break 2; // skaičius su kableliu pvz 2.5 (rodo kaip STRING)
                            case 5:
                                $amount = $matches[3] + $matches[4] / $matches[5] ; // "1 1/2" = 1.5
                                break 2;
                            case 4:
                                $valid = 'remove';
                        }

                    }
                }
//                exit($amount);
//                if (isset($matches[10]) && ($matches[10]!='')) {
//                    // sveikas skaičius pvz 2,8,40 (rodo kaip STRING)
//                } else if (isset($matches[9]) && ($matches[9]!='')){
//                    $amount = $matches[8] / $matches[9] ; // pvz "1/3" = 0.3(3)
//                } else if (isset($matches[6]) && ($matches[6]!='')){
//                    // skaičius su kableliu pvz 2.5 (rodo kaip STRING)
//                } else if (isset($matches[5]) && ($matches[5]!='')){
//                    $amount = $matches[3] + $matches[4] / $matches[5] ; // "1 1/2" = 1.5
//                } else $valid = 'remove';
            }
        }

        return array($amount,$valid);
    }
} 