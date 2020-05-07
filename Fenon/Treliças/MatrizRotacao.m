function [Rel]=MatrizRotacao(coss,senn)

    Rel=zeros(4);
    Rel(1,1)=coss;
    Rel(1,2)=senn;
    Rel(2,1)=-senn;
    Rel(2,2)=coss;
    Rel(3,3)=coss;
    Rel(3,4)=senn;
    Rel(4,3)=-senn;
    Rel(4,4)=coss;



end
