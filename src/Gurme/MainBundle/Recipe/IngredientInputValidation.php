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

            $this->notes = $this->getNotes();

            $this->unit = $this->getUnit();

            $this->amount = $this->getAmount();

            $this->ingredient = $this->getIngredient();

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

            $this->result = ($this->valid=='remove') ? false : $this->result;

            $i++;
        }

        return array('status' => $this->result, 'ingredients' => $ing);
    }

    /**
     * Get array of valid lines for reading
     *
     * @param $contents
     * @return array
     */
    private function getLinesArray($contents)
    {
        $lines = array();
        $searchFor = '';
        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if (preg_match_all($pattern, $contents, $matches)) {
            $lines = $matches[0];
            for ($i=0;$i<count($lines);$i++) {
                if ( trim($lines[$i]) == '' ) unset($lines[$i]);
            }
            $lines = array_values($lines);
        }
        else $this->result = false;

        return $lines;
    }

    /**
     * Reset variables before reading new line
     */
    private function reset()
    {
        $this->amount = null;
        $this->unit = null;
        $this->unitObject = null;
        $this->ingredient = null;
        $this->notes = null;
        $this->valid = false;
    }

    /**
     * Get notes in brackets or add $addToNotes string to the notes
     *
     * @param null|string $addToNotes
     * @return string
     */
    private function getNotes($addToNotes = null)
    {
        $notes = $this->notes;
        $pattern = '/\((.+)\)/';
        if(preg_match_all($pattern, $this->currentLine, $matches)){
            $notes = $matches[0][0];
            $this->currentLine = preg_replace($pattern, '', $this->currentLine);
        }
        if (!is_null($addToNotes)) {
            $notes = (!is_null($this->notes)) ?
                $addToNotes . ', ' . $this->notes : $addToNotes ;
        }

        return $notes;
    }

    /**
     * Match any unit in the line to the unit database units
     *
     * @return null
     */
    private function getUnit()
    {
        $this->currentLine = ' ' . $this->currentLine;
        $name = null;
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

    /**
     * Get amount at the beginning of the line
     *
     * @return null
     */
    private function getAmount()
    {
        $amount = null;
        if (!is_null($this->unit)) {
            preg_match('/^\s*([. 0-9\/]+) '.$this->unit.'/', $this->currentLine, $matches);
            if (isset($matches[1])) {
                $amount = $matches[1];
            }
        } else {
            if(preg_match('/^\s*([. 0-9\/]*)((.*),\s(.*)|(.*))/', $this->currentLine, $matches)) {
                $amount = $matches[1];

                if(trim($amount) != '') {
                    $this->unit = 'units';
                    $this->unitObject = $this->em->getRepository('GurmeMainBundle:Unit')
                        ->findOneBy(array('main' => 'unit'));
                } else $this->valid = 'ok';
            }
        }

        return $amount;
    }

    /**
     * Get ingredient name, and extract notes after comma
     *
     * @return string
     */
    private function getIngredient()
    {
        $ingredient = '';
        $unit = (strpos($this->unit,'unit') !== false) ? '' : $this->unit;
        $pattern = '/^\s*([. 0-9\/]*)'.$unit.'\s((.*),\s(.*)|(.*))/';
//        exit($pattern);
        if (preg_match($pattern, $this->currentLine, $matches)) {
            if (isset($matches[3])&&($matches[3]=='')) {
                $ingredient = $matches[2];
            } else {
                $ingredient = $matches[3];
                $this->notes = $this->getNotes($matches[4]);
            }
        } else $this->valid = 'remove';
        $ingredient = trim($ingredient);
        return $ingredient;
    }

    /**
     * Convert various types of number input into decimal number
     *
     * @return float|string
     */
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
            }
        }
        return $amount;
    }
} 