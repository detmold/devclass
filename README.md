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

To co powinniśmy zwrócić dla każdego testu to policzona funkcja kosztu f(c) dla danego typu

## Algorytm

Zadanie [DEVCLASS](https://www.codechef.com/problems/DEVCLASS) wskazówki do algorytmu: [video algorytm](https://youtu.be/hPhVPEiqluY)

1. Sprawdzenie czy w ogole da sie poprawnie przekształcić ciąg znaków. Jeśli różnica pomiędzy liczbą liter 'B' i 'G' w ciągu $s jest większa niż 1 to taki ciąg nie daje się
przekształcić do oczekiwanego formatu. Zwracamy wtedy -1.

Jeżeli liter 'B' i 'G' jest taka sama ilość w ciągu wejściowym $s to oznacza, że wejściowy ciąg może być pszekształcony do jednego z dwóch fromatów:
**BG..B[$n-1]G[$n-1]** lub **GB..G[$n-1]B[$n-1]** przy wyliczaniu funkcji kosztu obydwa formaty powinny być brane pod uwagę i ostatecznie koszt powinien być wzięty z tego gdzie był mniejszy. Ten ciąg przekształcony zapisujemy jako $t i $t1

Jeżli różnica pomiędzy liczbą liter 'B' i 'G' jest 1 to ciąg wejściowy $s może być pszekształcony do jednego formatu z zależności od tego jakiej litery jest więcej:
 - litery B jest więcej o 1 wtedy oczekiwany format to: **BG..B[$n-1]G[$n-1]B**
 - litery G jest więcej o 1 wtedy oczekiwany format to: **GB..G[$n-1]B[$n-1]G**
 jak widać nadmiarowa litera zostaje po prostu dodana na końcu. Ten ciąg przekształcony zapisujemy jako $t

 2. Tworzymy dwie tablice $b i $g oraz jeżeli przypadek $t1 to także tablicę $b1 i $g1. Przetwarzamy ciąg wejściowy $s w pętli z licznikiem $i i porównujemy $s[$i] do $t[$i] oraz jeżeli mamy przypadek, że istnieje $t1 to porównujemy również $s[$i] do $t1[$i] jeżeli: $s[$i] != $t[$i] to:
jeżli $s[$i] == 'B' robimy $b[] = $i oraz jeżli przypadek $t1 to także: $s[$i] == 'B' robimy $b1[] = $i
jeżeli $s[$i] == 'G' robimy $g[] = $i oraz jeżli przypadek $t1 to także: $s[$i] == 'G' robimy $g1[] = $i
w ten sposób w czasie liniowym O(n) tworzymy dwie listy elementów które nie znajdują się na swoich pozycjach. W przypadku kiedy mamy przypadek z $t i $t1 trzeba liczyć 
ilość zamian niezależnie dla $t i $t1 - do sprawdzenia czy faktycznie :P

3. Tworzymy zmienną $output i jeżeli $t to $output1. Robimy pętle po tablicy $b lub $g w zależności od tego która ma więcej elementów. Tutaj przyjąłem, że jest to $g. 
dla $t = 0 <=> $output += floor((count($b) + count($g)) / 2) jeżli przypadek $t1 to: $output1 += floor((count($b1) + count($g1)) / 2)), następnie robimy jeżli $t1:
$output = min($output, $output1)
W pętli liczymy:
dla $t > 0 <=> $output += abs|$g[$i] - $b[$i]| jeżli przypadek $t1 to: $output1 += abs|$g1[$i] - $b1[$i]|
po wyjściu z pętli: $output = min($output, $output1)



