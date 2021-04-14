figura :: Int -> Int -> Int -> Int -> Int -> String
figura a b c d ang 
  |a==b && b==c && c==d && ang /= 90 = "losango"
  |a==b && b==c && c==d && ang == 90 = "quadrado"
  |(a==b && c==d && ang==90) || (a==c && b==d && ang==90) || (a==d && b==c && ang==90) = "retangulo"
  |otherwise = "simples" 

-----------------------------------------------------------------------------------

seleciona :: [[Int]] -> [[Int]]
seleciona lista = [ys |(y:ys)<-lista,y>4]