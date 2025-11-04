class NewsBar {
  constructor({container="#newsBar",track="#newsTrack",speed=100,startDelay=1000,
               direction="rtl",pauseOnHover=true,theme="light",sound=false,animation="scroll"}={}){
    this.container=document.querySelector(container);
    this.track=document.querySelector(track);
    this.speed=speed;this.startDelay=startDelay;this.direction=direction;
    this.pauseOnHover=pauseOnHover;this.theme=theme;this.sound=sound;this.animation=animation;
    this.position=0;this.paused=false;this.width=0;this.animationFrame=null;this.events={};
    if(!this.container||!this.track)return console.error("NewsBar: container or track not found");
    window.addEventListener("load",()=>{this.setup();setTimeout(()=>this.start(),this.startDelay);});
    window.addEventListener("resize",()=>this.setup());
    if(this.pauseOnHover){this.container.addEventListener("mouseenter",()=>this.pause());
      this.container.addEventListener("mouseleave",()=>this.resume());}
    if(typeof document.hidden!=="undefined"){document.addEventListener("visibilitychange",()=>{
      if(document.hidden)this.pause();else this.resume();});}
  }
  setup(){cancelAnimationFrame(this.animationFrame);
    const originals=[...this.track.querySelectorAll("[data-original]")];
    if(originals.length===0)[...this.track.children].forEach(el=>el.setAttribute("data-original","true"));
    else{this.track.innerHTML="";originals.forEach(el=>this.track.appendChild(el.cloneNode(true)));}
    const content=this.track.innerHTML;this.track.innerHTML=content+content;
    this.width=this.track.scrollWidth/2;this.position=0;
    this.track.style.transform="translateX(0)";
    document.documentElement.setAttribute("data-theme",this.theme);}
  start(){this.emit("start");this.startTime=performance.now();this.animate();}
  animate(){if(this.paused)return;
    const now=performance.now();const elapsed=(now-this.startTime)/1000;
    const dir=this.direction==="rtl"?-1:1;
    this.position=(elapsed*this.speed*dir)%this.width;
    this.track.style.transform=`translateX(${this.position}px)`;
    this.animationFrame=requestAnimationFrame(()=>this.animate());}
  pause(){if(this.paused)return;this.paused=true;cancelAnimationFrame(this.animationFrame);
    this.container.classList.add("paused");this.emit("pause");}
  resume(){if(!this.paused)return;this.paused=false;this.startTime=performance.now();
    this.container.classList.remove("paused");this.animate();this.emit("resume");}
  setSpeed(v){this.speed=v;} setDirection(d){if(["rtl","ltr"].includes(d))this.direction=d;}
  setTheme(t){this.theme=t;document.documentElement.setAttribute("data-theme",t);}
  setItems(arr){this.track.innerHTML=arr.map(x=>`<span>${x}</span>`).join("");this.setup();}
  loadFrom(url){fetch(url).then(r=>r.json()).then(d=>{if(d.items){this.setItems(d.items);
    if(this.sound)new Audio('/assets/media/tick.mp3').play();}});}
  on(e,fn){if(!this.events[e])this.events[e]=[];this.events[e].push(fn);}
  emit(e,p){if(this.events[e])this.events[e].forEach(fn=>fn(p));}
}
window.NewsBar=NewsBar;