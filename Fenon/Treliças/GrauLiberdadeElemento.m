function [glel,nglno]=GrauLiberdadeElemento(el,nNoEL,Mpele)
aux5=3;  
nglno=aux5-1;
  for i=1:nNoEL
      no=Mpele(el,i);
      for j=1:nglno
          glel(nglno*(i-1)+j)=nglno*(no-1)+j;
      end
      
  end

end

