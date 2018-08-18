<?php
class Devclass {
    public $test;
    public $g = array();
    public $b = array();
    public $t;
    public $t1;
    public $compareType; // 0 - equal B and G, 1 - B one more than G, 2 - G one more than B
    public $n; //input string length

    private function calcMinCost($s, $t, $type) {

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
        $this->test = trim(stream_get_line(STDIN, 100000, PHP_EOL));
        for ($i=0; $i<$this->test; $i++) {
            $type = trim(stream_get_line(STDIN, 10, PHP_EOL));
            $s = trim(stream_get_line(STDIN, 100000, PHP_EOL));
            //sprawdzenie czy przypadek daje sie obsluzyc jesli ilosc |B-G| > 1 to musimy zwrocic -1
            // jesli |B-G| = 1 to oznacza, że musimy zrobić transformacje na ciąg t zaczynający się od nadmiarowej litery
            // jesli |B-G| = 0 to oznacza, że musimy sprawdzić dwa rodzaje ciągów t, zaczynający się od B lub G
            $letters = count_chars($s, 1);
            $this->n = strlen($s);
            if ($this->checkInput($letters)) {
                if ($this->n == 1 || $this->n == 2) {
                    echo 0 . PHP_EOL;
                }
                
            } else {
                echo -1 . PHP_EOL;
            }
        }

    }
}

$dev = new Devclass();
$dev->init();
