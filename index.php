<?php
class Devclass {
    public $test;
    public $g = array();
    public $b = array();
    public $g1 = array();
    public $b1 = array();
    public $t;
    public $t1;
    public $compareType; // 0 - equal B and G, 1 - B one more than G, 2 - G one more than B
    public $n; //input string length
    public $output = 0;
    public $type = 0;

    private function resetVaraiables() {
        $this->b = array();
        $this->g = array();
        $this->b1 = array();
        $this->g1 = array();
        $this->t = '';
        $this->t1 = '';
    }

    private function calcMinCost($s) {

        for ($i=0; $i<$this->n; $i++) {
            if ($s[$i] != $this->t[$i]) {
                if ($s[$i] == 'B') {
                    $this->b[] = $i;
                }  
                else if ($s[$i] == 'G') {
                    $this->g[] = $i;
                }
            }
            if (!empty($this->t1) && $s[$i] != $this->t1[$i]) {
                if ($s[$i] == 'B') {
                    $this->b1[] = $i;
                }  
                else if ($s[$i] == 'G') {
                    $this->g1[] = $i;
                }
            }
        }
        
    }

    private function calcOutput() {
        $output1 = INF;
        if ($this->type == 0) {  // przypadek dla testu = 0 liczymy tu po prostu koszt zmiany jako 1 czyli de facto robimy: ilosc_liter_nie_na_pozycjach / 2
            $this->output = floor((count($this->b) + count($this->g)) / 2);
            if (!empty($this->t1)) {  // tu dodatkowo sprawdzamy alternatywny przypadek 
                $output1 = floor((count($this->b1) + count($this->g1)) / 2);
            }
            $this->output = min($this->output, $output1); // ostatecznie wybieramy niższy koszy z dwóch wariantów
        }

        else if ($this->type > 0) { //przypadek dla testu > 0 wtedy f(c) musimy zsumować dla każdej nie pasującej do siebie pary elementów
            $counter = count($this->b) >= count($this->g) ? count($this->b) : count($this->g);
            $output1 = empty($this->t1) ? INF : 0;
            $this->output = 0;
            for ($k=0; $k<$counter; $k++) {
                $this->output += abs($this->g[$k] - $this->b[$k]);
            }
            if (!empty($this->t1)) {
                $counter = count($this->b1) >= count($this->g1) ? count($this->b1) : count($this->g1);
                for ($k=0; $k<$counter; $k++) {
                    $output1 += abs($this->g1[$k] - $this->b1[$k]);
                }
            }
            $this->output = min($this->output, $output1); 
        }
    }

    private function checkInput($letters) {
        $correct = true;
        $cmp = isset($letters[ord('G')]) && isset($letters[ord('B')]) ? $letters[ord('B')] - $letters[ord('G')] : 100;
        $correct = ((isset($letters[ord('G')]) && isset($letters[ord('B')])) && !(abs($cmp) > 1));
        $correct = isset($letters[ord('B')]) && $letters[ord('B')] == 1 && !isset($letters[ord('G')]) ? true : $correct;
        $correct = isset($letters[ord('G')]) && $letters[ord('G')] == 1 && !isset($letters[ord('B')]) ? true : $correct;
        switch ($cmp) {
            case 0:
                $this->compareType = 0;
                $this->t = str_repeat('BG', $this->n/2);
                $this->t1 = str_repeat('GB', $this->n/2);
                break;
            case 1:
                $this->compareType = 1;
                $this->t = str_repeat('BG', floor($this->n/2)) . 'B';
                break;
            case -1:
                $this->compareType = 2;
                $this->t = str_repeat('GB', floor($this->n/2)) . 'G';
                break;
            default:
                $this->compareType = -1;
        }
        return $correct;
    }

    public function init() {
        $this->test = trim(stream_get_line(STDIN, 1000000, PHP_EOL));
        for ($i=0; $i<$this->test; $i++) {
            $this->type = trim(stream_get_line(STDIN, 10, PHP_EOL));
            $s = trim(stream_get_line(STDIN, 1000000, PHP_EOL));
            //sprawdzenie czy przypadek daje sie obsluzyc jesli ilosc |B-G| > 1 to musimy zwrocic -1
            // jesli |B-G| = 1 to oznacza, że musimy zrobić transformacje na ciąg t zaczynający się od nadmiarowej litery
            // jesli |B-G| = 0 to oznacza, że musimy sprawdzić dwa rodzaje ciągów t, zaczynający się od B lub G
            $letters = count_chars($s, 1);
            $this->n = strlen($s);
            $this->resetVaraiables();
            if ($this->checkInput($letters)) {
                if ($this->n == 1 || $this->n == 2) {
                    $this->output = 0;
                } else {
                    $this->calcMinCost($s);
                    $this->calcOutput();
                }
            } else {
                $this->output = -1;
            }
            echo $this->output . PHP_EOL;
        }

    }
}

$dev = new Devclass();
$dev->init();
