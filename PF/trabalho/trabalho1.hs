--1-
analisa_raizes :: Int -> Int -> Int -> [Char]
analisa_raizes a b c 
  |a == 0 = "4-equação degenerada"
  |b*b > 4*a*c = "1-possui duas raizes reais"
  |b*b < 4*a*c = "2-nenhuma raiz real"
  |otherwise = "3-possui uma raiz real"

--2-
equacao :: Float -> Float -> Float -> (Float,Float)
equacao a b c = if a != 0 then ((b-(b*b-4*a*c))/2*a,(b+(b*b-4*a*c))/2*a)
                    else (-c/b,a)

--3-
type Data = (Int,Int,Int)

precede :: Data->Data->Bool
precede (dia1,mes1,ano1)(dia2,mes2,ano2) 
  |mes1 < mes2 = True
  |mes1 == mes2 && dia1<dia2 = True
  |otherwise = False

onibus :: Float -> Data -> Data -> Float
onibus preco (diahj,meshj,anohj) (diab,mesb,anob) = if precede (diahj,meshj,anohj) (diab,mesb,anob)
                                                    then 
                                                      |anohj-anob>70 = preco*0.5
                                                      |anohj-anob<2 = preco*0.15
                                                      |anohj-anob<10 = preco*0.4
                                                      |otherwise = preco
                                                    else 
                                                      |anohj-anob>71 = preco*0.5
                                                      |anohj-anob<3 = preco*0.15
                                                      |anohj-anob<11 = preco*0.4
                                                      |otherwise = preco

--4-
--a)
gera1 :: [Int] -> [Int]
gera1 y = [x*x*x|x<-y,even(x),x>0,x<21]
--b)
gera2 :: [Int] -> [(Int),(Int)]
gera2 z = [(x,y)|x<-z,x<=5,y<-[x..3*x]]
--c)
l1=[15,16]
gera3 :: [Int]
gera3 = [z | z<-x,[(x,y)|x<-[1..y],y<-[l1]]]
--d)
gera4 :: [(Int,Int)]
gera4 = [(x,y)|x,y<-[1..10],even(x),y==x+1]
--e
gera5 :: [Int]
gera5 = [x |y,z<-gera4,x==y+z]
--5-
--a
conta :: [t] -> Int
conta [] = 0
conta (_:xs) = 1 + conta xs
--
contaNegM2 :: [Int] -> Int
contaNegM2 lista = [conta(x) |x<- lista,x>0,x%3==0]
--b
listaNegM2 :: [Int] -> [Int]
listaNegM2 lista = [x |x<- lista,x>0,x%3==0]
--6-
fatores :: Int -> [Int]
fatores x = [y |y<-[2..x/2],z%y==0]
--
primos :: Int -> Int -> [Int]
primos start end = [x |x<-[start..end],fatores x==[]]
--7-
mdc :: Int -> Int -> Int
mdc a b 
  |a < b = mdc b a
  |mod b a == 0 = b
  |otherwise = mdc b (mod a b)
mmc2 :: Int -> Int -> Int
mmc2 a b 
  |a == 0 || b == 0 = 0
  |a == b = a
  |otherwise = (a*b)/(mdc a b)
mmc3 :: Int -> Int -> Int -> Int
mmc3 a b c = mmc2 a (mmc2 b c)
--8-
serie :: Float -> Float -> [Float]
serie x n =if n>0 then 
  |odd n = n/x + serie x n-1
  |otherwise = x/n + serie x n-1
  else 0
--9-
fizzbuzz :: Int->[Str]
fizzbuzz n = [x |y<[1..n],x |y % 2 == 0 && y % 3 == 0 = "FizzBuzz" 
                            |y % 2 == 0 = "Fizz"
                            |y % 3 == 0 = "Buzz"
                            |otherwise = "No"]
--10-
fatorescompleto :: Int -> [Int]
fatorescompleto x = [y |y<-[2..x],z%y==0]
pertence :: t -> [t] -> Bool
pertence y [] = False
pertence y (x:xs) = if y == x then True
                        else pertence y xs
seleciona_multiplos :: Int -> [Int] -> [Int]
seleciona_multiplos n lista = [x |x<-lista,pertence n (fatorescompleto x)]
--11-
contagem :: t -> [t] -> Int
contagem elem [] = 0
contagem elem (x:xs) =if x == elem then 1 + contagem elem xs else 0 + conta elem xs
unica_ocorrencia :: t -> [t] -> Bool
unica_ocorrencia elem lista = if (contagem elem lista) != 1 then True else False
