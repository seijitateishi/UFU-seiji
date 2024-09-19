objetivo <- function(x) {
  f = (x * sin(10 * pi * x)) + 1
  return(f)
}

# parametros:
# - max numero de geracoes (tmax)
# - valor inicial de x (x0)

# (\mu+\lambda)-EE
ee <- function(tmax, x, n) {
  # contador
  t = 0

  f_ant = objetivo(x)
  
  while (t < tmax) {
    # gerar uma perturbacao que varia de -1 e 2
    z = runif(n = n, min = -1, max = 2)
    
    # gerar um novo individuo
    y = x + z
    f_nov = objetivo(y)

    for (i in 1:n) {
      # vamos comparar o valor de f_nov com o PIOR individuo de f_ant (max)
      # se ele for *melhor que o pior* da geracao anterior, ele substitui o pior
      if (f_nov[i] < max(f_ant)) {
        # encontra a posicao do pior individuo
        indice = which.max(f_ant)
        
        # substitui o valor do pai
        x[indice] = y[i]

        # subsitui o valor da funcao objetivo
        # para nao ter que recalcular
        f_ant[indice] = f_nov[i]
      }
    }
    
    # incrementa contador
    t = t + 1
  }
  cat("Tmax = ", tmax, "Melhor = ", min(f_ant))
  ret = list()
  ret$x = x
  ret$f = f_ant
  return(ret)
}

tmax = 10 #numero de  
inc = tmax
numexp = 50 #mu / lambda 
while (tmax <= inc*5){
  x0 = runif(n = numexp, min = -2, max = 2)#inicio variando entre -2 e 2
  ee(tmax = tmax, x = x0, n = length(x0))
  tmax = tmax + inc
}