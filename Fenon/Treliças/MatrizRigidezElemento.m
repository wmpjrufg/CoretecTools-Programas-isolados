
function [Kel]=MatrizRigidezElemento(E,A,L)


    Kel=zeros(4);
    Kel(1,1)=E*A/L;
    Kel(1,3)=-E*A/L;
    Kel(3,1)=-E*A/L;
    Kel(3,3)=E*A/L;



end

