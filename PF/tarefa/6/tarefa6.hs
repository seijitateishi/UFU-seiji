--valida :: Data -> Bool
--valida (dia,mes,ano)
--  |dia <1 || dia >31 || mes <1 || mes >12 = False
--  |dia >30 && (mes == 2 || mes == 4 || mes == 6 || mes == 9 || mes == 11 )  = False
--  |dia >29 && mes == 2 = False
--  |dia >28 && mes == 2 && not(bissexto ano) = False
--  |otherwise = True
type Data = (Int,Int,Int)
--where
valida :: Data -> Bool
valida (dia,mes,ano)
  |dia <1 || dia >31 || mes <1 || mes >12 = False
  |dia >30 && (mes == 2 || mes == 4 || mes == 6 || mes == 9 || mes == 11 )  = False
  |dia >29 && mes == 2 = False
  |dia >28 && mes == 2 && not(bissexto ano) = False
  |otherwise = True
    where 
        bissexto :: Int -> Bool
        bissexto ano
          |mod ano 400 == 0 = True
          |mod ano 4 == 0 = True
          |otherwise = False
--let          
valida :: Data -> Bool
let 
    bissexto :: Int -> Bool
    bissexto ano
      |mod ano 400 == 0 = True
      |mod ano 4 == 0 = True
      |otherwise = False
in 
    valida (dia,mes,ano)
      |dia <1 || dia >31 || mes <1 || mes >12 = False
      |dia >30 && (mes == 2 || mes == 4 || mes == 6 || mes == 9 || mes == 11 )  = False
      |dia >29 && mes == 2 = False
      |dia >28 && mes == 2 && not(bissexto ano) = False
      |otherwise = True
    
--
--where
bissextos :: [Int] -> [Int]
bissextos [] = []
bissextos (x:xs) =if bissexto x then x:bissextos xs 
                    else bissextos xs
    where 
    bissexto :: Int -> Bool
    bissexto ano
      |mod ano 400 == 0 = True
      |mod ano 4 == 0 = True
      |otherwise = False
--let
let
    bissexto :: Int -> Bool
    bissexto ano
      |mod ano 400 == 0 = True
      |mod ano 4 == 0 = True
      |otherwise = False
in
    bissextos :: [Int] -> [Int]
    bissextos [] = []
    bissextos (x:xs) =if bissexto x then x:bissextos xs 
                        else bissextos xs
   
--
--type Emprestimo = (String,String,Data,Data,String)
--type Emprestimos = [Emprestimo]
--precede :: Data->Data->Bool
--precede (dia1,mes1,ano1)(dia2,mes2,ano2) 
--  |not(valida (dia1,mes1,ano1)) || not (valida(dia2,mes2,ano2)) = False
--  |ano1<ano2 = True
--  |ano1 == ano2 && mes1 < mes2 = True
--  |ano1 == ano2 && mes1 == mes2 && dia1<dia2 = True
--  |otherwise = False
--atrasados :: Emprestimos->Data->[Emprestimo]
--atrasados emp hoje = [(idlivro,idaluno,data1,data2,sit) | (idlivro,idaluno,data1,data2,sit)<-emp ,precede data2 hoje]
--where
atrasados :: Emprestimos -> Data -> [Emprestimos]
atrasados emp hoje = [(idlivro,idaluno,data1,data2,sit) | (idlivro,idaluno,data1,data2,sit)<-emp ,precede data2 hoje]
    where
        precede :: Data->Data->Bool
        precede (dia1,mes1,ano1)(dia2,mes2,ano2) 
          |not(valida (dia1,mes1,ano1)) || not (valida(dia2,mes2,ano2)) = False
          |ano1<ano2 = True
          |ano1 == ano2 && mes1 < mes2 = True
          |ano1 == ano2 && mes1 == mes2 && dia1<dia2 = True
          |otherwise = False
            where 
                valida :: Data -> Bool
                valida (dia,mes,ano)
                  |dia <1 || dia >31 || mes <1 || mes >12 = False
                  |dia >30 && (mes == 2 || mes == 4 || mes == 6 || mes == 9 || mes == 11 )  = False
                  |dia >29 && mes == 2 = False
                  |dia >28 && mes == 2 && not(bissexto ano) = False
                  |otherwise = True
                    where 
                        bissexto :: Int -> Bool
                        bissexto ano
                          |mod ano 400 == 0 = True
                          |mod ano 4 == 0 = True
                          |otherwise = False
--let
atrasados :: Emprestimos -> Data -> [Emprestimos]
let bissexto :: Int -> Bool
                        bissexto ano
                          |mod ano 400 == 0 = True
                          |mod ano 4 == 0 = True
                          |otherwise = False    
in 
    valida :: Data -> Bool
                valida (dia,mes,ano)
                  |dia <1 || dia >31 || mes <1 || mes >12 = False
                  |dia >30 && (mes == 2 || mes == 4 || mes == 6 || mes == 9 || mes == 11 )  = False
                  |dia >29 && mes == 2 = False
                  |dia >28 && mes == 2 && not(bissexto ano) = False
                  |otherwise = True
    let
        precede :: Data->Data->Bool
                precede (dia1,mes1,ano1)(dia2,mes2,ano2) 
                  |not(valida (dia1,mes1,ano1)) || not (valida(dia2,mes2,ano2)) = False
                  |ano1<ano2 = True
                  |ano1 == ano2 && mes1 < mes2 = True
                  |ano1 == ano2 && mes1 == mes2 && dia1<dia2 = True
                  |otherwise = False
    in 
        atrasados emp hoje = [(idlivro,idaluno,data1,data2,sit) | (idlivro,idaluno,data1,data2,sit)<-emp ,precede data2 hoje]

--
--passo(x,y)
fibo2 :: Int -> Int
fibo2 x = 

--
--where
fatorial :: Int -> Int
fatorial x = prodIntervalo 1 x
    where
        prodIntervalo :: Int->Int->Int
        prodIntervalo n n = n
        prodIntervalo m n = m*prodIntervalo(m+1)
--let
let 
    prodIntervalo :: Int->Int->Int
    prodIntervalo n n = n
    prodIntervalo m n = m*prodIntervalo(m+1)
in 
    fatorial :: Int -> Int
    fatorial x = prodIntervalo 1 x