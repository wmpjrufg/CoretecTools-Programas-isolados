function [K]=RigidezEstrutura(Mcoor,E,Mpsec,Mpele,el,nNoEL,K)
  
Mcoor
E
Mpsec
Mpele
el
nNoEL
       [A,L,coss,senn]=Propriedades(Mcoor,Mpsec,Mpele,el);
A
L
coss
senn       
       
       [Kel]=MatrizRigidezElemento(E,A,L);
       Kel
       [Rel]=MatrizRotacao(coss,senn);
       [Kelg]=MatrizRigidezGlobal(Kel,Rel);
       [glel,nglno]=GrauLiberdadeElemento(el,nNoEL,Mpele);
       [K]=MatrizTransferencia(Kelg,nglno,nNoEL,glel,K);
      
       

end

