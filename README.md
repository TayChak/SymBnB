# SymBnB

<h3>Install deps</h3>
<pre>composer update</pre>

<h3> Install assets </h3>
<ol>
  <li> Insatll the npm packages 
    <pre>npm install</pre>
  </li>
  <li> Run  web pack 
    <pre>npm run dev</pre>
  </li>
</ol>
<h3> Databse </h3>
<ol>
  <li> Create Database
    <pre>php bin/console doctrine:database:create</pre>
  </li>
  <li> Create tables
    <pre>php bin/console doctrine:migrations:migrate</pre>
  </li>
  <li> Insert data to tables
    <pre>php bin/console doctrine:fixtures:load</pre>
  </li> 
</ol>

 <p>Copyright © <strong><a href="https://github.com/TayChak/SymBnB/" target="_blank">Tayeb Chakroun</a></strong> 2021</p>
