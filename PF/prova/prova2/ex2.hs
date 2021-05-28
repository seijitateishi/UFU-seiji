totaliza :: [Int] -> Int
totaliza (x:xs) = foldr1 (*) (filter (\x -> mod x 2 == 0) (x:xs))

addTriplas :: [(Int,Int,Int)] -> [Int] 
addTriplas x = filter (\x -> x mod 2 == 0) (map ((a, b, c) -> a + b + c) x)

--Luis Gustavo Seiji TAteishi 
--11921BCC034
