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
onibus :: Float -> Data -> Data -> Float
onibus preco hoje nascimento 
  |