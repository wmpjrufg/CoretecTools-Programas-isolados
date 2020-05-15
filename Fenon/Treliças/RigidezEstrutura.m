function [K]=RigidezEstrutura(Mcoor,E,Mpsec,Mpele,el,nNoEL,K)
  

       [A,L,coss,senn]=Propriedades(Mcoor,Mpsec,Mpele,el);
      
       
       [Kel]=MatrizRigidezElemento(E,A,L);
       
       [Rel]=MatrizRotacao(coss,senn);
       [Kelg]=MatrizRigidezGlobal(Kel,Rel);
       [glel,nglno]=GrauLiberdadeElemento(el,nNoEL,Mpele);
       [K]=MatrizTransferencia(Kelg,nglno,nNoEL,glel,K);
      
       

end

