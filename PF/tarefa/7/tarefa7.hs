--1-
paridade :: [Int] -> [Bool]
paridade x = map even x
--2-
prefixos :: [String] -> [String]
prefixos x = map (take 3) x
--3-
saudacao :: [String] -> [String]
saudacao x = map ("Oi "++)x
--4-
--f :: a -> Bool
filtrar :: (a -> Bool) -> [a] -> [a]
filtrar f (x:xs) = [z |z<-(x:xs),f z]
--5-
pares :: [Int] -> [Int]
pares x = filter even x   
--6-
solucoes :: [Int] -> [Int]
solucoes x = filter (\y -> (5*y+6) < (y*y)) x
--7-
maior :: [Int] -> Int
maior x = foldr1 max x
--8-
menor_min10 :: [Int] -> Int
menor_min10 x = foldr min 10 x
--9-
junta_silabasplural :: [String] -> String
junta_silabasplural x = foldr (++) "s" x 
--10-
menores10 :: [Int] -> ([Int],Int)
menores10 x = ((filter (<10) x,length (filter (<10) x)))
--11

busca :: Int -> [Int] -> (Bool,Int)
busca num x = if ((filter (==num) x) == []) then (False,length x) else (True,espaco num x)
    where   
        espaco :: Int -> [Int] -> Int
        espaco num (x:xs) = if num==x then 1 else 1+espaco num xs