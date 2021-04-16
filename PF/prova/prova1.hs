figura :: Int -> Int -> Int -> Int -> Int -> String
figura a b c d ang 
  |a==b && b==c && c==d && ang /= 90 = "losango"
  |a==b && b==c && c==d && ang == 90 = "quadrado"
  |(a==b && c==d && ang==90) || (a==c && b==d && ang==90) || (a==d && b==c && ang==90) = "retangulo"
  |otherwise = "simples" 

-----------------------------------------------------------------------------------

--seleciona :: [[Int]] -> [[Int]]
--seleciona lista = [ys |(y:ys)<-lista,y>4]

-----------------------------------------------------------------------------------

pertence :: Eq t => t -> [t] -> Bool
pertence y [] = False
pertence y (x:xs) = if y == x then True

elimina_repet :: Eq t => t -> [t] -> [t]
elimina_repet _ [] = []
elimina_repet x xs = if pertence x xs then elimina_repet xs
                        else x : elimina_repet xs

retira_varias :: [t] -> [t] -> [t]
retira_varias [] _ = []
retira_varias _ [] = []
-retira_varias (x:xs) (y:ys) = elimina_repet x (y:ys) : retira_varias xs (y:ys) 

-----------------------------------------------------------------------------------
reverte :: [t] -> [t]
reverte [] = []
reverte (x:xs) = reverte xs ++ [x]
[1,2,3]
_sequencia :: String -> String ->Bool
_sequencia [] _ = True
_sequencia (x:xs) (y:ys) = if x==y then _sequencia xs ys else False

sequencia :: String -> String -> Bool
sequencia [] _ = True
sequencia sufixo palavra = _sequencia (reverte sufixo) (reverte palavra)

-----------------------------------------------------------------------------------
vogais = "aeiou"
dicionario = ["arara","arreio","haskell", "puxa","peixe","pixar","pixe", "vaca","vacuo","velho","vermelho","vicio"]
retira_vogal :: String -> String
retira_vogal (x:xs) = if x=='a' || x== 'e' || x== 'i' || x== 'o' || x== 'u' 
                        then retira_vogal xs 
                      else x:retira_vogal xs
representante :: [String] -> String -> [String]
representante [] _ = []
representante (dicionario:xs) consoante =  if (retira_vogal dicionario) == consoante 
                                                  then dicionario:representante xs consoante 
                                                else representante xs consoante

dicionario = ["cola",“calo”,"cala"]
sequencia de consoantes: “cl”                                                