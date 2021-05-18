--
l1=[1..2000]
l2=[2000,1999..1]
l3=l1++[0]
l4=[0]++l2
l5=l1++[0]++l2
l6=l2++[0]++l1
l7=l2++[0]++l2
x1=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
x2=[20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1]
x3=[11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9,10]
x4=[10,9,8,7,6,5,4,3,2,1,20,19,18,17,16,15,14,13,12,11]
x5=[11,12,13,14,15,5,4,3,2,1,16,17,18,19,20,10,9,8,7,6]
x6=[1,12,3,14,5,15,4,13,2,11,6,17,8,19,20,10,9,18,7,16]
x7=[20,8,2,11,13,3,7,18,14,4,16,10,15,1,9,17,19,12,5,6]
--
bolha :: Ord a => [a] -> [a]
bolha [] = []
bolha lista = bolhaOrd lista (length lista)

bolhaOrd :: Ord a => [a] -> Int -> [a]
bolhaOrd lista 0 = lista
bolhaOrd lista n = bolhaOrd (troca lista) (n-1)

troca :: Ord a => [a] -> [a]
troca [x] = [x]
troca (x:y:zs)
  |x > y = y : troca (x:zs)
  |otherwise = x : troca (y:zs)
--
--variação 1
bolha1 :: Ord a => [a] -> [a]
bolha1 [] = []
bolha1 lista = bolhaOrd1 lista (length lista)

bolhaOrd1 :: Ord a => [a] -> Int -> [a]
bolhaOrd1 lista 0 = lista
bolhaOrd1 lista n = bolhaOrd1 (troca1 lista) (n-1)

troca1 :: Ord a => [a] -> [a]
troca1 [x] = [x]
troca1 (x:y:zs)
  |x > y = y : troca1 (x:zs)
  |otherwise = x : troca1 (y:zs)