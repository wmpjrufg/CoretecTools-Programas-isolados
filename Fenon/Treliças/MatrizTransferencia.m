function [K]=MatrizTransferencia(Kelg,nglno,nNoEL,glel,K)
ngle=nNoEL*nglno;

for i=1:ngle
    for j=1:ngle
        K(glel(i),glel(j))=K(glel(i),glel(j))+ Kelg(i,j);
    end
end

    
end