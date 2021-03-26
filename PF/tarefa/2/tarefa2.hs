dobro :: Int -> Int 
dobro x = x * 2

quadruplo :: Int -> Int
quadruplo x = dobro (dobro x)

hip ::Float -> Float -> Float 
hip a b = sqrt((a**2)+(b**2))

distancia :: Float -> Float -> Float -> Float -> Float
distancia xa ya xb yb = sqrt(((xa-xb)*(xa-xb))+((ya-yb)*(ya-yb)))

conversao :: Float -> (Float,Float,Float)
conversao real = (real,real/3.96,real/4.45)

bissexto :: Int -> Bool
bissexto ano
  |mod ano 400 == 0 = True
  |mod ano 4 == 0 = True
  |otherwise = False

type Data = (Int,Int,Int)

bissexto2 :: Data -> Bool
bissexto2 (dia,mes,ano) = if bissexto ano then True 
        else False

valida :: Data -> Bool
valida (dia,mes,ano)
  |dia <1 || dia >31 || mes <1 || mes >12 = False
  |dia >30 && (mes == 2 || mes == 4 || mes == 6 || mes == 9 || mes == 11 )  = False
  |dia >29 && mes == 2 = False
  |dia >28 && mes == 2 && not(bissexto ano) = False
  |otherwise = True

precede :: Data->Data->Bool
precede (dia1,mes1,ano1)(dia2,mes2,ano2) 
  |not(valida (dia1,mes1,ano1)) || not (valida(dia2,mes2,ano2)) = False
  |ano1<ano2 = True
  |ano1 == ano2 && mes1 < mes2 = True
  |ano1 == ano2 && mes1 == mes2 && dia1<dia2 = True
  |otherwise = False

type Livro = (String,String,String,String,Int)
type Aluno = (String,String,String,Int)
type Emprestimo = (String,String,Data,Data,String)

verifica :: Emprestimo->Data->Bool
verifica (idlivro,idaluno,data1,data2,sit) hoje 
  |sit == "fechado" = True
  |not(valida hoje) = False
  |precede hoje data1 = False
  |(precede data1 hoje || data1 == hoje) && precede data1 data2 && (precede hoje data2 || hoje == data2) = True
  |otherwise = False