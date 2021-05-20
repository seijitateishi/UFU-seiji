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
x7 = [20,8,2,11,13,3,7,18,14,4,16,10,15,1,9,17,19,12,5,6]

{- Seleção original -}

selecao :: Ord a => [a] -> [a]
selecao [] = []
selecao xs = [x] ++ selecao (remove x xs)
    where
        x = minimo xs

remove :: Ord a => a->[a]->[a]
remove a [] = []
remove a (x:xs)
    | a == x = xs
    | otherwise = x:(remove a xs)

minimo :: Ord a => [a] -> a
minimo [] = undefined
minimo [x] = x
minimo (x:xs)
    | x <= minimo xs = x
    | otherwise = minimo xs


{- - Variação1: Refaça o código original para que a busca pelo menor elemento (função mínimo) ea eliminação desse menor elemento da lista a ser ordenada (função remove) ocorra numamesma função (remove_menor), sem a necessidade de se percorrer a lista duas vezes a cadaiteração. -}


{- Variação 2: Refaça a implementação do algoritmo Seleção usando funções genéricas (foldr oufoldr1) . -}
selecaofoldr :: (Ord a) => [a] -> [a]
selecaofoldr [] = []
selecaofoldr xs = [x] ++ selecaofoldr (remove x xs)
  where 
    x = foldr1 min xs

insercao :: (Ord a) => [a] -> [a]
insercao [] = []
insercao (x:xs) = insereOrd x (insercao xs)

insereOrd :: (Ord a) => a -> [a] -> [a]
insereOrd x [] = [x]
insereOrd x (y:ys)
  |x <= y = (x:y:ys)
  |otherwise = y : (insereOrd x ys)    

