--3-
--a)
lista3 :: (Int,Int)->[Int]
lista3 (a,b) =
    if a<b then [a..b]
        else (if a==b then [a]
            else [])

--b)
lista3b :: (Int,Int)->[Int]
lista3b (a,b) = 
    if a<b then (if even a then [a,a+2..b]
                    else [a+1,a+3..b])
        else[]

--5-
listaquad :: (Int,Int)->[Int]
listaquad (a,b) = [x*x | x<-[a..b]]

--6-
seleciona_impares :: [Int]->[Int]
seleciona_impares lista = [x | x<-lista,odd x]

--7-
tabuada :: Int->[Int]
tabuada num = [x*num | x<-[1..10]]

--8-
bissexto :: Int -> Bool
bissexto ano
  |mod ano 400 == 0 = True
  |mod ano 4 == 0 = True
  |otherwise = False
bissextos:: [Int]->[Int]
bissextos anos = [x |x<-anos,bissexto x]

--9-
sublistas :: [[Int]]->[Int]
sublistas x = concat x


type Data = (Int,Int,Int)

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

--10-
type Emprestimos = [Emprestimo]

bdEmprestimo::Emprestimos
bdEmprestimo =
 [("H123C9","BSI945",(12,9,2009),(20,09,2009),"aberto"),
 ("L433C5","BCC021",(01,9,2009),(10,09,2009),"encerrado"),
 ("M654C3","BCC008",(04,9,2009),(15,09,2009),"aberto")]

atrasados :: Emprestimos->Data->[Emprestimo]
atrasados emp hoje = [(idlivro,idaluno,data1,data2,sit) | (idlivro,idaluno,data1,data2,sit)<-emp ,precede data2 hoje]

--11-
uniaoNRec :: [Int]->[Int]->[Int]
uniaoNRec lista1 lista2 = lista1++[x |x<-lista2,not(elem x lista1)]