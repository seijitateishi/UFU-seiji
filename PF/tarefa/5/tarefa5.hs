--1-
conta_ch :: [Char] -> Int
conta_ch [] = 0
conta_ch (x:xs) = 1 + conta_ch xs
--
conta :: [t] -> Int
conta [] = 0
conta (_:xs) = 1 + conta xs
--
maior :: [Int] -> Int
maior [x] = x
maior (x:y:xs) =    if x >= y then maior (x:xs)
                        else maior (y:xs)
--
maior2 :: [Int] -> Int
maior2 [x] = x
maior2 (x:y:xs)
  |x > y = maior (x:xs)
  |otherwise = maior (y:xs)
--
primeiros :: Int -> [t] -> [t]
primeiros 0 _ = []
primeiros _ [] = []
primeiros y (x:xs) = x: primeiros (y-1) xs
--
pertence :: t -> [t] -> Bool
pertence y [] = False
pertence y (x:xs) = if y == x then True
                        else pertence y xs
--
uniaoR :: [t] -> [t] -> [t]
uniaoR [] y = y
uniaoR (x:xs) y =   if pertence x y then uniaoR xs y
                        else x:uniaoR xs y
--2-
npares :: [Int] -> Int
npares [] = 0
npares (x:xs) = if x % 2 == 0 then (1 + conta xs)
                    else (0 + conta xs)
--3-
produtorio :: [Int] -> Int
produtorio [] = 0
produtorio [x] = x
produtorio [y:x:xs] = y * x * produtorio xs
--4-
comprime :: [[Int]] -> [Int]
comprime [] = []
comprime [x] = [x]
comprime [x:xs] = uniaoR x:xs
--5-
tamanho :: [t] -> [Int]
tamanho [] = 0
tamanho [x:xs] = 1+tamanho xs
--6-
uniaoRec2 :: [t] -> [t] -> [t]
uniaoRec2 x [] = x
uniaoRec2 [] x = x
uniaoRec2 [y:ys] [x:xs] =    