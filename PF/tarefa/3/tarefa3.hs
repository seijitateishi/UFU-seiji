--1-a)
ou1 :: Bool->Bool->Bool
True True = True
a False = a
False a = a
False False = False

ou2 :: Bool->Bool->Bool
False False = False
_ _ = True

ou3 :: Bool->Bool->Bool
True _ = True
_ True = True
_ _ = False

--1-b)
ouR1 :: Bool->Bool->Bool
ouR1 a b = if (not(a) && not(b)) then True else False

ouR2 :: Bool->Bool->Bool
ouR2 a b 
  |not a && not b = False
  |otherwise = True

--2-
distancia :: Float -> Float -> Float -> Float -> Float
distancia xa ya xb yb = sqrt(((xa-xb)*(xa-xb))+((ya-yb)*(ya-yb)))

--3-
--guarda
fatorialg :: Int->Int
fatorialg x 
 |fatorialg 0 = 1
 |fatorialg 1 = 1
 |otherwise = x * fatorialg (x-1)
--casamento padrao
fatorialcp :: Int->Int
fatorialcp 0 = 0
fatorialcp 1 = 0 
fatorialcp = fatorialcp*(fatorialcp-1)

--4-
fibo :: Int->Int
fibo 0 = 0
fibo 1 = 1
fibo x = fibo (x-1) + fibo (x-2)

--5-
n_tri :: Int->Int
n_tri 0 = 0
n_tri x = (x*(x+1))/2

--6-
potencia2 :: Int->Int
potencia2 0 = 1
potencia2 1 = 2
potencia2 x = 2**(x-1) * 2

--7-
--a)
prodIntervalo :: Int->Int->Int
prodIntervalo n n = n
prodIntervalo m n = m*prodIntervalo(m+1)

--b)
fatorialint :: Int->Int
fatorialint 0 = 1
fatorialint x = prodIntervalo 1 x 

--8-
resto_div :: Int->Int->Int
resto_div a b 
  |b>a = a
  |otherwise = resto_div (a-b) b 

div_inteira :: Int->Int->Int
div_inteira a b 
  | a - b < b 
  |otherwise = 1 + div_inteira(a-b) b

--9-
--guardas
mdcg :: Int->Int
mdcg a b 
  |n == 0 = m
  |n>0 = mdcg(n,m%n)

--casamento de padroes
mdccp :: Int->Int
mdccp (m,0) = m
mdccp (m,n) =   if (n>0) then mdccp (n,m%n)
                else -1

--10-
--guarda
binomialg :: (Int,Int)->Int
binomialg (n,k) 
  |k==n = 1
  |k==0 = 1
  |0<k && k<n = binomial (n-1,k)+binomial (n-1,k-1)
  |otherwise = -1

--casamento de padroes
binomialcp :: (Int,Int)->Int
binomialcp (n,0) = 1
binomialcp (k,k) = 1
binomialcp (n,k) =  if (0<k && k<n) then binomialcp(n-1,k)+binomialcp(n-1,k-1)
                    else -1

--11-
--a)
fiboa::Int->(Int,Int)
fiboa x = (fibo x,fibo (x+1))

--b)