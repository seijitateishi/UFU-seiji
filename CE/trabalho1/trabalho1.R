objetivo <- function(x) {
  f = (x * sin(10 * pi * x)) + 1
  return(f)
}

# parametros:
# - max numero de geracoes (tmax)
# - valor inicial de x (x0)

# (\mu+\lambda)-EE
ee <- function(tmax, mu, lambda) {
  # contador
  t = 0
  f_ant = objetivo(x0)
  
  while (t < tmax) {
    # gerar uma perturbacao que varia de -1 e 2
    z = runif(n = lambda, min = -1, max = 2) 
    
    # gerar um novo individuo
    y = mu + z
    f_nov = objetivo(y)
    
    for (i in 1:lambda) {
      # vamos comparar o valor de f_nov com o PIOR individuo de f_ant (max)
      # se ele for *melhor que o pior* da geracao anterior, ele substitui o pior
      if (f_nov[i] > min(f_ant)) {
        # encontra a posicao do pior individuo
        index = which.max(f_ant)
        
        # substitui o valor do pai
        mu[index] = y[i]
        
        # subsitui o valor da funcao objetivo
        # para nao ter que recalcular
        f_ant[index] = f_nov[i]
      }
    }
    
    # incrementa contador
    t = t + 1
  }
  melhor = max(f_ant)
  media = mean(f_ant)
  desvio_padrao = sd(f_ant)
  mulenght = length(mu)
  cat(tmax,",",mulenght,",",lambda,",", melhor,",",media, ",", desvio_padrao, "\n")
  ret = list()
  ret$mu = mu
  ret$f = f_ant
  return(ret)
}

tmax = 10 #numero de iteracoes
mu = 10#quantidade de pais
lambda = 10#lambda = quantidade de filhos
inc = tmax
while (tmax <= inc*10){
  x0 = runif(n = mu, min = -1, max = 2)#estado inicial dos experimento entre -1 e 2
  ee(tmax = tmax, mu = x0, lambda = lambda)#n = lambda (numero de filhos)
  tmax = tmax + inc
}
