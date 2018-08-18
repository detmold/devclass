# devclass

DEVCLASS - Devu and his Class

## Problem

Na wejściu dostajemy liczbę testów i dla każdego testu mamy: 
- typ oznaczamy jako **$t**, przy czym typ należy do zbioru <0,1,2> i określa funkcję kosztu.
- ciąg znaków oznaczamy jako **$s**, 
- długość ciągu $s jako **$n** 
Funkcja kosztu jest zdefiniowana następująco: f(c) = |j-i|^t gdzie i oraz j to indeksy w wejściowym ciągu znaków $s.
Ciąg znaków $s może się składać z 1 <= $s <= 10^5 znaków 'B' lub 'G'. Mając do dyspozycji tylko zamianę pojedynczych liter miejscami powinno się tak przekształcić
ciąg $s, że żadne dwie takie same litery nie znajdują się obok siebie. 

Przykłady:
```php
$s = 'BBBGGG'; 
$output = 'BGBGBG'; //lub
$output = 'GBGBGB';

$s = 'BBGGG';
$output = 'GBGBG'

$s = 'B';
$output = 'B';

$s = 'BBBG';
$output = -1; //jesli liczba liter B i G rozni się o wiecej nic 1 to nie mozemy utworzyc poprawnego ciagu znakow
```

To co powinniśmy zwrócić dla każdego testu to wynik policzonej funkcja kosztu f(c) dla danego typu $t

## Algorytm

Zadanie [DEVCLASS](https://www.codechef.com/problems/DEVCLASS) wskazówki do algorytmu: [video algorytm](https://youtu.be/hPhVPEiqluY)

1. Sprawdzenie czy w ogóle da sie poprawnie przekształcić ciąg znaków. Jeśli różnica pomiędzy liczbą liter 'B' i 'G' w ciągu $s jest większa niż 1 to taki ciąg nie daje się
przekształcić do oczekiwanego formatu. Zwracamy wtedy -1.

Jeżeli liter 'B' i 'G' jest taka sama ilość w ciągu wejściowym $s to oznacza, że wejściowy ciąg może być przekształcony do jednego z dwóch fromatów:
**BG..B[$n-1]G[$n-1]** lub **GB..G[$n-1]B[$n-1]** przy wyliczaniu funkcji kosztu obydwa formaty powinny być brane pod uwagę i ostatecznie koszt powinien być wzięty z tego gdzie był mniejszy. Te dwa oczekiwane przekształcone ciągi zapisujemy jako $t i $t1

Jeżli różnica pomiędzy liczbą liter 'B' i 'G' jest 1 to ciąg wejściowy $s może być przekształcony do jednego formatu z zależności od tego jakiej litery jest więcej:
 - litery B jest więcej o 1 wtedy oczekiwany format to: **BG..B[$n-1]G[$n-1]B**
 - litery G jest więcej o 1 wtedy oczekiwany format to: **GB..G[$n-1]B[$n-1]G**
 jak widać nadmiarowa litera zostaje po prostu dodana na końcu. Ten ciąg przekształcony zapisujemy jako $t

 2. Tworzymy dwie tablice $b i $g oraz jeżeli przypadek $t1 to także tablicę $b1 i $g1. Przetwarzamy ciąg wejściowy $s w pętli z licznikiem $i i porównujemy $s[$i] do $t[$i] oraz jeżeli mamy przypadek, że istnieje $t1 to porównujemy również $s[$i] do $t1[$i] jeżeli: $s[$i] != $t[$i] to:
```php 
// variables init
$b = array();
$g = array();
$b1 = array();
$g1 = array();

for ($i=0; $i<$n; $i++) {
    if ($s[$i] != $t[$i]) {
        if ($s[$i] == 'B') {
            $b[] = $i;
        }  
        else if ($s[$i] == 'G') {
            $g[] = $i;
        }
    }

    if (!empty($t1) && $s[$i] != $t1[$i]) {
        if ($s[$i] == 'B') {
            $b1[] = $i;
        }  
        else if ($s[$i] == 'G') {
            $g1[] = $i;
        }
    }
}


```
w ten sposób w czasie liniowym O(n) tworzymy dwie listy elementów które nie znajdują się na swoich pozycjach. W przypadku kiedy mamy przypadek z $t i $t1 trzeba liczyć 
ilość zamian niezależnie dla $t i $t1 - do sprawdzenia czy faktycznie :P

3. Tworzymy zmienną $output i jeżeli $t1 to $output1. Robimy pętle po tablicy $b lub $g w zależności od tego która ma więcej elementów. Tutaj przyjąłem, że jest to $g. 
```php
$output1 = INF;
if ($t == 0) {  // przypadek dla testu = 0 liczymy tu po prostu koszt zmiany jako 1 czyli de facto robimy: ilosc_liter_nie_na_pozycjach / 2
    $output = floor((count($b) + count($g)) / 2);
    if (!empty($t1)) {  // tu dodatkowo sprawdzamy alternatywny przypadek 
        $output1 = floor((count($b1) + count($g1)) / 2));
    }
    $output = min($output, $output1); // ostatecznie wybieramy niższy koszy z dwóch wariantów
}

else if ($t > 0) { //przypadek dla testu > 0 wtedy f(c) musimy zsumować dla każdej nie pasującej do siebie pary elementów
    $counter = count($b) >= count($g) ? count($b) : count($g);
    $output1 = empty($t1) ? INF : 0;
    $output = 0;
    for ($k=0; $k<$counter; $k++) {
        $output += abs($g[$k] - $b[$k]);
    }
    if (!empty($t1)) {
        $counter = count($b1) >= count($g1) ? count($b1) : count($g1);
        for ($k=0; $k<$counter; $k++) {
            $output1 += abs($g1[$k] - $b1[$k]);
        }
    }
    $output = min($output, $output1); 
}

echo $output . PHP_EOL; // zmienna output powinna zawierać policzony koszt w/g typu dostarczonego z zewnątrz
```

## Bonus

Dlaczego nie sprawdzamy osobno dla typu = 2 ? Dla typu równego 2 i każdego większego mamy koszt identyczny jak dla typu = 1  
- **Dowód**
1. Dzieje się tak dlatego, że koszt każdego przekształcenia f(c) = |j-i|^t gdzie t > 1 daje się wyrazić jako sumę dwóch przekształceń pośrednich takich jak:  
f(c) = |j-k|^t + |k-i|^t gdzie j > k > i zachodzi tutaj taka prawidłowść, że: |j-k|^t + |k-i|^t < |j-i|^t  

2. Z kolei każde przekształcenie pośrednie można wyrazić jako sumę: |i+1-i|^t = 1 dla każgeo t > 0  
co za tym idzie każde wyrażenie f(c) = |j-i|^t możemy wyrazić jako sumę: |i+1-i|^t = 1 od i do j  
przykład:  
dla j=4, i=0, k=2, t=2 mamy:  
f(c) = |4-0|^2 = 16  // jeśli liczymy osobną metodą dla t = 2, koszt jaki uzyskamy = 16  
f(c) = |4-2|^2 + |2-0|^2 = 4+4 = 8  // jeśli liczymy sumę dwóch przekształceń pośrednich koszt jest niższy = 8  
f(c) = |1-0|^2 + |2-1|^2 + |3-2|^2 + |4-3|^2 = 4  // jeśli liczymy metodą przkształceń pośrednich, gdzie różnica między j a i jest = 1 mamy najmniejszy koszt a co  
za tym idzie jest to najbardziej optymalna pod względem minimalizacji kosztu metoda, bo jak było wcześniej pokazane możemy sumą zmian indeksów oddalonych o 1  
uzykać to samo co pojedynczą zamianą indeksów bardziej oddalonych ale jak widać koszt przy tak zdefiniowanej funkcji f(c) będzie najniższy w przypadku ostatniej metody  


