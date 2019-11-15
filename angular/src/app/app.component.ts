import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { User } from './User';
import { Fields } from './Fields';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  user: User;
  fields: Fields;

  constructor(private http: HttpClient) {}

  ngOnInit() {
    // Make the HTTP request:
    this.http.get<User>('http://localhost:8000/auth').subscribe(data => {
      this.user = data;
    });

    // List of things i want to show: "first_name,last_name,picture,birthday,gender,posts";
    var accessToken = 'EAAGbrjEyK6QBAOBdYbVtvdY5xqZCx7RiAifj4LhyslAxfdzAZBIkmC6wtg8OUJXbVbfQgpw6D7Sinpybkds61AnmX77EWpMzmbFIZBZA33ZCqhJsZB5QpjynvNPK7qmnHd7ZAnl5mj9z0Tl3dp3nCFOL3OrotWZA6ncbaNM9zZCN28kcNizZBKUc7r';

    this.http.get('https://graph.facebook.com/v5.0/me?fields=picture,first_name%2Cbirthday,last_name,gender,posts&access_token=EAAGbrjEyK6QBAOBdYbVtvdY5xqZCx7RiAifj4LhyslAxfdzAZBIkmC6wtg8OUJXbVbfQgpw6D7Sinpybkds61AnmX77EWpMzmbFIZBZA33ZCqhJsZB5QpjynvNPK7qmnHd7ZAnl5mj9z0Tl3dp3nCFOL3OrotWZA6ncbaNM9zZCN28kcNizZBKUc7r').subscribe(result => {
      console.log(result);
      this.fields = result;
    });
}
}
