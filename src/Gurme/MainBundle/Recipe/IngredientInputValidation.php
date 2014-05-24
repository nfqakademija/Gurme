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
     * @var boolean
     */
    private $result;

    /**
     * @var string
     */
    private $currentLine;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var Unit
     */
    private $unitObject;

    /**
     * @var string
     */
    private $ingredient;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $valid;

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
        $this->result = true;
        $ing = array();

        $lines = $this->getLinesArray($contents);

        $i=0;
        foreach ($lines as $line) {

            $this->currentLine = $line;

            $this->reset();
//            $ing[$i]['valid']   = 'remove';
//            $ing[$i]['amount']  = '';
//            $ing[$i]['ingredient'] = '';

            $this->notes = $this->getNotesInBrackets();

            // Find any notes within brackets. e.g. (4 ponds)
//            $pattern = '/\((.+)\)/';
//            $matches = [];
//            if(preg_match_all($pattern, $line, $matches)){
//                $ing[$i]['notes'] = $matches[0][0];
//                $line = preg_replace($pattern, '', $line);
//            }

            $this->unit = $this->getUnit();
//            list($unitMatched,$ing[$i]['unit'],$ing[$i]['unitObj']) = $this->getUnit($this->currentLine);

            $this->amount = $this->getAmount();


            $this->ingredient = $this->getIngredient();


//            // Get amount and ingredient data from line
//            if (!is_null($this->unit)) {
//                $matches = [];
//                preg_match('/^\s*([. 0-9\/]+) '.$ing[$i]['unit'].'/', $line, $matches);
//                if (isset($matches[1])) {
//                    $ing[$i]['amount'] = $matches[1];
//                }
//                $pattern = '/^.*'.$ing[$i]['unit'].'\s*((.*),\s*(.*)|(.*))/';
//                $matches = [];
//                preg_match($pattern, $line, $matches);
//                if (isset($matches[3]) && $matches[3]!='')
//                {
//                    $ing[$i]['ingredient'] = $matches[2];
//                    $ing[$i]['notes'] = (isset($ing[$i]['notes']) ?
//                        $matches[3] . ' ' . $ing[$i]['notes'] : $matches[3]);
//                }
//                else
//                {
//                    $ing[$i]['ingredient'] = $matches[1];
//                }
//            } else {
//                $matches = [];
//                if(preg_match('/^\s*([. 0-9\/]*)((.*),\s(.*)|(.*))/', $line, $matches)) {
//                    if (isset($matches[3])&&($matches[3]=='')&&($matches[3]=='')) {
//                        $ing[$i]['amount'] = $matches[1];
//                        $ing[$i]['ingredient'] = $matches[2];
//                    } else {
//                        $ing[$i]['amount'] = $matches[1];
//                        $ing[$i]['ingredient'] = $matches[3];
//                        $ing[$i]['notes'] = (isset($ing[$i]['notes']) ?
//                            $matches[4] . ' ' . $ing[$i]['notes'] : $matches[4]);
//                    }
//                    if(trim($ing[$i]['amount']) != '') {
//                        $ing[$i]['unit'] = 'units';
//                        $ing[$i]['unitObj'] = $this->em->getRepository('GurmeMainBundle:Unit')
//                            ->findOneBy(array('main' => 'unit'));
//                    } else $ing[$i]['valid'] = 'ok';
//                }
//            }

            $this->amount = $this->convertToMetric();

            $ingredient = array(
                'amount'    => $this->amount,
                'unit'      => $this->unit,
                'unitObj'   => $this->unitObject,
                'ingredient' => $this->ingredient,
                'notes'     => $this->notes,
                'valid'     => $this->valid
            );

            $ing[]=$ingredient;

//            list($ing[$i]['amount'],$ing[$i]['valid']) = $this->convertToMetric($ing[$i]['amount'],$ing[$i]['valid']);

            $this->result = ($this->valid=='remove') ? false : $this->result;

            $i++;
        }

        return array('status' => $this->result, 'ingredients' => $ing);
    }

    private function reset()
    {
        $this->amount = null;
        $this->unit = null;
        $this->ingredient = null;
        $this->notes = null;
    }

    private function getNotesInBrackets()
    {
        $notes = null;
        // Find any notes within brackets. e.g. (4 ponds)
        $pattern = '/\((.+)\)/';
        $matches = [];
        if(preg_match_all($pattern, $this->currentLine, $matches)){
            $notes = $matches[0][0];
            $this->currentLine = preg_replace($pattern, '', $this->currentLine);
        }
        return $notes;
    }

    private function getUnit()
    {
        $this->currentLine = ' ' . $this->currentLine;
        $name = null;
        $object = null;
        $units = $this->repository->findAll();
        /** @var Unit $unit */
        foreach ($units as $unit) {
            if (null !== $unit->getMain()) {
                $pattern = (null !== $unit->getPlural()) ? '('.$unit->getMain().'|'.$unit->getPlural().')' : $unit->getMain() ;
                $pattern = '/\s('.$pattern.'s?)\s/';
                if (preg_match($pattern, $this->currentLine, $matches)) {
                    //exit($matches[0]);
                    $name = $matches[1];
                    $this->unitObject = $unit;
                }
            }
        }

        return $name;
    }

    private function convertToMetric()
    {
        $amount = trim($this->amount);
        if ($amount != '') {
            $pattern = '/((([0-9]*)\s+([0-9]*)\/([0-9]*))|([0-9]*\.[0-9]*)|(([0-9]*)\/([0-9]*))|([0-9]*))/';
            if (preg_match($pattern,$amount,$matches)) {
                $this->valid = 'ok';
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
                                $this->valid = 'remove';
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

        return $amount;
    }

    private function getAmount()
    {
        $amount = null;
        // Get amount and ingredient data from line
        if (!is_null($this->unit)) {
            $matches = [];
            preg_match('/^\s*([. 0-9\/]+) '.$this->unit.'/', $this->currentLine, $matches);
            if (isset($matches[1])) {
                $amount = $matches[1];
            }
            $pattern = '/^.*'.$this->unit.'\s*((.*),\s*(.*)|(.*))/';
            $matches = [];
            preg_match($pattern, $this->currentLine, $matches);
            if (isset($matches[3]) && $matches[3]!='')
            {
                $this->notes = (!is_null($this->notes) ?
                    $matches[3] . ' ' . $this->notes : $matches[3]);
            }
        } else {
            $matches = [];
            if(preg_match('/^\s*([. 0-9\/]*)((.*),\s(.*)|(.*))/', $this->currentLine, $matches)) {
                if (isset($matches[3])&&($matches[3]=='')&&($matches[3]=='')) {
                    $amount = $matches[1];
                } else {
                    $amount = $matches[1];
                    $this->notes = (!is_null($this->notes) ?
                        $matches[4] . ' ' . $this->notes : $matches[4]);
                }
                if(trim($amount) != '') {
                    $this->unit = 'units';
                    $this->unitObject = $this->em->getRepository('GurmeMainBundle:Unit')
                        ->findOneBy(array('main' => 'unit'));
                } else $this->valid = 'ok';
            }
        }
        return $amount;
    }

    private function getIngredient()
    {
        $ingredient = '';
        if (!is_null($this->unit) && $this->unit != 'unit' && $this->unit != 'units') {
            $pattern = '/^.*'.$this->unit.'\s*((.*),\s*(.*)|(.*))/';
            $matches = [];
            preg_match($pattern, $this->currentLine, $matches);
            if (isset($matches[3]) && $matches[3]!='')
            {
                $ingredient = $matches[2];
                $this->notes = (!is_null($this->notes) ?
                    $matches[3] . ', ' . $this->notes : $matches[3]);
            }
            else
            {
                $ingredient = $matches[1];
            }
        } else {
            if(preg_match('/^\s*([. 0-9\/]*)((.*),\s(.*)|(.*))/', $this->currentLine, $matches)) {
                if (isset($matches[3])&&($matches[3]=='')&&($matches[3]=='')) {
                    $ingredient = $matches[2];
                } else {
                    $ingredient = $matches[3];
                    $this->notes = (!is_null($this->notes) ?
                        $matches[4] . ', ' . $this->notes : $matches[4]);
                }
            }
        }
        $ingredient = trim($ingredient);
        return $ingredient;
    }

    private function getLinesArray($contents)
    {
        $lines = array();
        $searchFor = '';
        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if (preg_match_all($pattern, $contents, $matches)) {
            //exit(var_dump(trim($matches[0])));
            $lines = $matches[0];
            for ($i=0;$i<count($lines);$i++) {
                if ( trim($lines[$i]) == '' ) unset($lines[$i]);
            }
            $lines = array_values($lines);
        }
        else $this->result = false;

        return $lines;
    }
} 