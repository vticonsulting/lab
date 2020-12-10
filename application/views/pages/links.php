

<section class="section">
<ul>
  <li>
    <a href="#">Wavy Wavy Wavy</a>
  </li>
  <li>
    <a href="#">Zig Zag Zig Zag</a>
  </li>
  <li>
    <a href="#">Let's Goooo!</a>
  </li>
</ul>
</section>

<style>
.section {
  height: 100vh;
  width: 100vw;
  overflow: hidden;
  display: grid;
  -webkit-box-pack: center;
          justify-content: center;
  align-content: center;
  background: #fff;
}
.section ul {
  list-style-type: none;
  background: #1155cb;
  padding: 30px 80px 40px 90px;
  border-radius: 5px;
  box-shadow: 0 40px 30px -20px rgba(0, 0, 0, 0.25);
}
.section ul li {
  text-align: left;
  position: relative;
}
.section ul li:hover:after {
  box-shadow: 0 0 0 5px #1155cb, 0 0 0 7px #e0ff00;
}
.section ul li:after {
  content: '';
  position: absolute;
  width: 7.5px;
  height: 7.5px;
  background: #c2ff00;
  border-radius: 100%;
  left: -25px;
  top: 50%;
  -webkit-transform: translate(0, -50%);
          transform: translate(0, -50%);
  box-shadow: 0 0 0 0 #1155cb, 0 0 0 0 #e0ff00;
  -webkit-transition: 0.4s ease-in-out;
  transition: 0.4s ease-in-out;
}
.section ul li:nth-of-type(2) a:before {
  bottom: -50px;
  -webkit-transform-origin: left;
          transform-origin: left;
}
.section ul li:nth-of-type(2) a:hover:before {
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200%25' height='100%25'%3E%3Cdefs%3E%3Cstyle%3E .wave%7B animation:wave 2s cubic-bezier(0.175, 0.885, 0.32, 1) infinite; animation-delay:-0.25s; stroke:%23e0ff00; stroke-width:2; stroke-linecap:square; %7D @keyframes wave%7B 25%25%7B d:path('M 0 20 L 10 15 L 20 20 L 30 25 L 40 20 ');%0A%7D%0A50%25%7B%0Ad:path('M 0 20 L 10 25 L 20 20 L 30 15 L 40 20  ');%0A%7D%0A75%25%7B%0Ad:path('M 0 20 L 10 15 L 20 20 L 30 25 L 40 20 ');%0A%7D %7D %3C/style%3E%3C/defs%3E%3Cpattern id='wavePattern' x='0' y='0' width='40' height='40' patternUnits='userSpaceOnUse'%3E%3Cpath fill='none' class='wave' d='M 0 20 L 10 25 L 20 20 L 30 15 L 40 20' /%3E%3C/pattern%3E%3Crect x='0' y='0' width='100%25' height='100%25' fill='url(%23wavePattern)'%3E%3C/rect%3E%3C/svg%3E") 0px 50%/40px 40px repeat-x;
  animation: waving 6s linear infinite reverse;
  -webkit-transform-origin: right;
          transform-origin: right;
}
.section ul li:nth-of-type(3) a:before {
  -webkit-transform-origin: right;
          transform-origin: right;
  width: calc(100% + 25px);
}
.section ul li:nth-of-type(3) a:hover:before {
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200%25' height='100%25'%3E%3Cdefs%3E%3Cstyle%3E .wave%7B animation:wave 2s linear infinite; animation-delay:0s; stroke:%23e0ff00; stroke-width:2; stroke-dashoffset:0px; stroke-dasharray:80px; stroke-linecap:round; fill:%231155cb; %7D @keyframes wave%7B 25%25%7B stroke-dashoffset:-80px; %7D 50%25%7B stroke-dashoffset:-80px; %7D 100%25%7B stroke-dashoffset:-160px; %7D %7D %3C/style%3E%3C/defs%3E%3Cpattern id='wavePattern' x='0' y='0' width='80' height='80' patternUnits='userSpaceOnUse'%3E%3Cpath class='wave' d='M 0 40 L 50 40 L 80 40 L 54 44 L 54 36 L 80 40 ' /%3E%3C/pattern%3E%3Crect x='0' y='0' width='100%25' height='100%25' fill='url(%23wavePattern)'%3E%3C/rect%3E%3C/svg%3E") calc(100% - 10px) 50%/40px 80px no-repeat, url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200%25' height='100%25'%3E%3Cdefs%3E%3Cstyle%3E .wave%7B stroke:%23e0ff00; stroke-width:2; stroke-linecap:round; %7D %3C/style%3E%3C/defs%3E%3Cpattern id='wavePattern' x='0' y='0' width='80' height='80' patternUnits='userSpaceOnUse'%3E%3Cpath fill='none' class='wave' d='M 0 40 Q 20 40 40 40 Q 60 40 80 40' /%3E%3C/pattern%3E%3Crect x='0' y='0' width='100%25' height='100%25' fill='url(%23wavePattern)'%3E%3C/rect%3E%3C/svg%3E") 0px 50%/calc(100% - 25px) 80px no-repeat;
  -webkit-animation: none;
          animation: none;
  -webkit-transform-origin: left;
          transform-origin: left;
}
.section a {
  display: inline-block;
  margin: 20px 0;
  position: relative;
  color: #fff;
  font-family: "Futura";
  text-decoration: none;
  font-size: 22px;
  z-index: 2;
  -webkit-transition: 0.2s ease-in-out;
  transition: 0.2s ease-in-out;
}
.section a:hover {
  color: #c2ff00;
}
.section a:hover:before {
  -webkit-transition: -webkit-transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transition: -webkit-transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transition: transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transition: transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275), -webkit-transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
  -webkit-transform-origin: left;
          transform-origin: left;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200%25' height='100%25'%3E%3Cdefs%3E%3Cstyle%3E .wave%7B animation:wave 1s ease-in-out infinite alternate; animation-delay:-0.25s; stroke:%23e0ff00; stroke-width:2; stroke-linecap:square; %7D @keyframes wave%7B to%7B d:path('M 0 40 Q 20 42.5 40 40 Q 60 37.5 80 40'); %7D %7D %3C/style%3E%3C/defs%3E%3Cpattern id='wavePattern' x='0' y='0' width='80' height='80' patternUnits='userSpaceOnUse'%3E%3Cpath fill='none' class='wave' d='M 0 40 Q 20 37.5 40 40 Q 60 42.5 80 40' /%3E%3C/pattern%3E%3Crect x='0' y='0' width='100%25' height='100%25' fill='url(%23wavePattern)'%3E%3C/rect%3E%3C/svg%3E") 0px 50%/80px 80px repeat-x;
  -webkit-animation: waving 3s linear infinite;
          animation: waving 3s linear infinite;
  -webkit-transform: scaleX(1);
          transform: scaleX(1);
}
@-webkit-keyframes waving {
  to {
    background-position: 80px 50%, 160px 50%;
  }
}
@keyframes waving {
  to {
    background-position: 80px 50%, 160px 50%;
  }
}
.section a:before {
  content: "";
  position: absolute;
  width: 100%;
  height: 80px;
  left: 0;
  bottom: -45px;
  z-index: -1;
  -webkit-transform: scaleX(0);
          transform: scaleX(0);
  -webkit-transition: -webkit-transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1);
  transition: -webkit-transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1);
  transition: transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1);
  transition: transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1), -webkit-transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1);
  -webkit-transform-origin: right;
          transform-origin: right;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200%25' height='100%25'%3E%3Cdefs%3E%3Cstyle%3E .wave%7B stroke:%23e0ff00; stroke-width:2; stroke-linecap:square; %7D %3C/style%3E%3C/defs%3E%3Cpattern id='wavePattern' x='0' y='0' width='80' height='80' patternUnits='userSpaceOnUse'%3E%3Cpath fill='none' class='wave' d='M 0 40 Q 20 40 40 40 Q 60 40 80 40' /%3E%3C/pattern%3E%3Crect x='0' y='0' width='100%25' height='100%25' fill='url(%23wavePattern)'%3E%3C/rect%3E%3C/svg%3E") 0px 50%/80px 80px repeat-x;
}
</style>
