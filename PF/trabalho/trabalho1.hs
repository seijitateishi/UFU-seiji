--1-
analisa_raizes :: Int -> Int -> Int -> [Char]
analisa_raizes a b c 
  |a == 0 = "4-equação degenerada"
  |b*b > 4*a*c = "1-possui duas raizes reais"
  |b*b < 4*a*c = "2-nenhuma raiz real"
  |otherwise = "3-possui uma raiz real"

--2-
equacao :: Float -> Float -> Float -> (Float,Float)
equacao a b c = if a /= 0 then ((b-(b*b-4*a*c))/2*a,(b+(b*b-4*a*c))/2*a)
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
                                                      if anohj-anob>70 then preco*0.5 else
                                                      if anohj-anob<2 then preco*0.15 else
                                                      if anohj-anob<10 then preco*0.4 else
                                                      preco
                                                    else 
                                                      if anohj-anob>71 then preco*0.5 else
                                                      if anohj-anob<3 then preco*0.15 else
                                                      if anohj-anob<11 then preco*0.4 else
                                                       preco 

--4-
--a
gera1 :: [Int] -> [Int]
gera1 y = [x*x*x|x<-y,even(x),x>0,x<21]
--b)
gera2 :: [Int] -> ((Int),(Int))
gera2 z = [(x,y)|x<=5,y<-[x..3*x]]
--c)
l1=[15,16]
gera3 :: [Int]
gera3 = [x |x<- [1..l1]]
--d)
gera4 :: [(Int,Int)]
gera4 = [(x,y)|x<-[1..10],y<-[1..10],even x,y==x+1]
--e
gera5 :: [(Int,Int)] -> [Int]
gera [] = []
gera5 ((x,y),xs) = [a+b |a<-[x,xs],b<-[y,xs]]
--5-
--a
conta :: [t] -> Int
conta [] = 0
conta (_:xs) = 1 + conta xs
--
contaNegM2 :: [Int] -> Int
contaNegM2 lista = [conta(x) |x<- lista,x>0,mod x 3==0]
--b
listaNegM2 :: [Int] -> [Int]
listaNegM2 lista = [x |x<- lista,x>0,mod x 3==0]
--6-
fatores :: Int -> [Int]
fatores x = [y |y<-[2..x/2],mod x y==0]
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
  if odd n then n/x + serie x n-1 else
  x/n + serie x n-1
  else 0
--9-
fizzbuzzAux :: Int -> [String]
fizzbuzzAux 1 = ["No"]
fizzbuzzAux n  |mod n 2 == 0 && mod n 3 == 0 = "FizzBuzz" 
               |mod n 2 == 0 = "Fizz"
               |mod n 3 == 0 = "Buzz"
               |otherwise = "No"
fizzbuzz :: Int -> [String]
fizzbuzz n = reverte (fizzbuzzAux (n))
--10-
fatorescompleto :: Int -> [Int]
fatorescompleto x = [y |y<-[2..x],mod x y==0]
pertence :: t -> [t] -> Bool
pertence y [] = False
pertence y (x:xs) = if y == x then True
                        else pertence y xs
seleciona_multiplos :: Int -> [Int] -> [Int]
seleciona_multiplos n lista = [x |x<-lista,pertence n (fatorescompleto x)]
--11-
contagem :: t -> [t] -> Int
contagem elem [] = 0
contagem elem (x:xs) =if x == elem then 1 + contagem elem xs else 0 + contagem elem xs
unica_ocorrencia :: t -> [t] -> Bool
unica_ocorrencia elem lista = if (contagem elem lista) /= 1 then True else False
--12-
intercala :: [t] -> [t] -> [t]
intercala x [] = x 
intercala [] x = x
intercala (x:xs) (y:ys) = x : y : intercala xs ys
--13-
zipar :: [t] -> [t] ->[[(t,t)]]
zipar x [] = []
zipar [] x = []
zipar (x:xs) (y:ys) = [x,y] : zipar xs ys
--14-
type Contato = (String,String,String,String)
recuperar :: String -> Contato -> String
recuperar aux (nome,_,_,email) = if aux==email then nome else "Email desconhecido"
--15-
type Pessoa = (String, Float, Int, Char)
pessoas :: [Pessoa]
pessoas = [("Rosa", 1.66, 27,'S'),
           ("Joao", 1.85, 26, 'C'),
           ("Maria", 1.55, 62, 'S'),
           ("Jose", 1.78, 42, 'C'),
           ("Paulo", 1.93, 25, 'S'),
           ("Clara", 1.70, 33, 'C'),
           ("Bob", 1.45, 21, 'C'),
           ("Rosana", 1.58,39, 'S'),
           ("Daniel", 1.74, 72, 'S'),
           ("Jocileide", 1.69, 18, 'S')]
medheight :: [Pessoa] -> Float
medheight [(_,x,_,_),xs]  = (x + medheight xs)/(conta [(_,x,_,_),xs])
--
youngest :: [Pessoa] -> Int
youngest [x,y] = if x<y then x else y
youngest [(_,_,x,_):(_,_,y,_):xs] = if x<y then youngest [(_,_,x,_):xs]
                                      else [(_, _, y,_):xs]
--
ageSum :: [Pessoa] -> Int
ageSum [] = 0
ageSum [(_, _, x, _),xs] = x + ageSum xs
oldestname :: [Pessoa] -> (String,String)
oldestname ((nome, _, idade, ec),xs) = if ((nome, _, idade,ec),xs) == idade then (nome,ec) 
                                      else oldestname xs
--
olderThan50 :: [Pessoa] -> [Pessoa]
olderThan50 ((_, _, idade, _),xs) = [x |x<-[(_, _, idade,_),xs],idade>=50]
--  
marriedOlderThan :: Int -> [Pessoa] -> Int
marriedOlderThan x ((_, _, idade, ec),xs) =  [length y |y<-[(_,_,idade,ec),xs],idade >= x,ec == 'C']
--16
insere_ord :: t -> [t] -> [t]
insere_ord elem [] = elem
insere_ord elem (x:xs) 
  |x >= elem = elem : x : xs
  |otherwise = x : insere_ord elem xs
--17
reverte :: [t] -> [t]
reverte [] = []
reverte (x:xs) = reverte xs ++ [x]
--18
elimina_repet :: [t] -> [t]
elimina_repet [] = []
elimina_repet (x:xs) = if pertence x xs then elimina_repet xs
                        else x : elimina_repet xs