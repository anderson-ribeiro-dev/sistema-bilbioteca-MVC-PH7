<?php 
    class ViewModels {
        //métodos
        protected function getViewModels($view){
          $listRouter = ["adminlist", "adminsearch", "admin", "bookconfig", "bookinfo", "book", "catalog", "categorylist", "category", "clientlist", "clientsearch", "client", "companylist", "company", "home", "myaccount", "mydata", "providerlist", "provider", "search"];  

          if (in_array($view, $listRouter)) {
              if (is_file("view/conteudo/" . $view . "-view.php")) {
                $login = "view/conteudo/" . $view . "-view.php";
              } else {
                $login = "login";  
              }
              
          } elseif($view === "login")  {
              $login = "login";
          } elseif($view === "index") {
              $login= "login";
          } else {
              $login = "login";
          }

          return $login;
        }

    }
