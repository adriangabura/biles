(function(B){var A={series:{stack:null}};function C(F){function D(J,I){var H=null;for(var G=0;G<I.length;++G){if(J==I[G]){break}if(I[G].stack==J.stack){H=I[G]}}return H}function E(W,P,G){if(P.stack==null){return }var L=D(P,W.getData());if(!L){return }var T=G.pointsize,Y=G.points,H=L.datapoints.pointsize,S=L.datapoints.points,N=[],R,Q,I,a,Z,M,O=P.lines.show,K=P.bars.show,J=O&&P.lines.steps,X=0,V=0,U;while(true){if(X>=Y.length){break}U=N.length;if(V>=S.length||S[V]==null||Y[X]==null){for(m=0;m<T;++m){N.push(Y[X+m])}X+=T}else{R=Y[X];Q=Y[X+1];a=S[V];Z=S[V+1];M=0;if(R==a){for(m=0;m<T;++m){N.push(Y[X+m])}N[U+1]+=Z;M=Z;X+=T;V+=H}else{if(R>a){if(O&&X>0&&Y[X-T]!=null){I=Q+(Y[X-T+1]-Q)*(a-R)/(Y[X-T]-R);N.push(a);N.push(I+Z);for(m=2;m<T;++m){N.push(Y[X+m])}M=Z}V+=H}else{for(m=0;m<T;++m){N.push(Y[X+m])}if(O&&V>0&&S[V-T]!=null){M=Z+(S[V-T+1]-Z)*(R-a)/(S[V-T]-a)}N[U+1]+=M;X+=T}}if(U!=N.length&&K){N[U+2]+=M}}if(J&&U!=N.length&&U>0&&N[U]!=null&&N[U]!=N[U-T]&&N[U+1]!=N[U-T+1]){for(m=0;m<T;++m){N[U+T+m]=N[U+m]}N[U+1]=N[U-T+1]}}G.points=N}F.hooks.processDatapoints.push(E)}B.plot.plugins.push({init:C,options:A,name:"stack",version:"1.0"})})(jQuery);