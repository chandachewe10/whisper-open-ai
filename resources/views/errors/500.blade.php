<x-guest-layout>    
<style>   


html,
body {
  height: 100%;
  margin: 0;
}

body {
  background: linear-gradient(#111, #333, #111);
  background-repeat: no-repeat;
  background-size: cover;
  color: #eee;
  position: relative;
  font-family: "Roboto", sans-serif;
}

.message {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}
.message h1, .message h2, .message h3 {
  margin: 0;
  line-height: 0.8;
}
.message h2, .message h3 {
  font-weight: 300;
  color: #C8FFF4;
}
.message h1 {
  font-weight: 700;
  color: #03DAC6;
  font-size: 8em;
}
.message h2 {
  margin: 30px 0;
}
.message h3 {
  font-size: 2.5em;
}
.message h4 {
  display: inline-block;
  margin: 0 15px;
}
.message button {
  background: transparent;
  border: 2px solid #C8FFF4;
  color: #C8FFF4;
  padding: 5px 15px;
  font-size: 1.25em;
  transition: all 0.15s ease;
  border-radius: 3px;
}
.message button:hover {
  background: #03DAC6;
  border: 2px solid #03DAC6;
  color: #111;
  cursor: pointer;
  transform: scale(1.05);
}


</style>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
<div class="message">
	<h1>500</h1>
	<h3>The Server is tired</h3>
    <br>
	<h3>It's not you, it's the server.</h3>
    <p>If this persists please contact the Adminstrator at support@speechtext.ai.</p>
	<!-- use window.history.back(); to go back -->
	<button><a href="{{config('app.url')}}">Go Back</button>
</div>

</x-guest-layout>