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
  fields: any;

  constructor(private http: HttpClient) {}

  ngOnInit() {
    // Make the HTTP request:
    this.http.get<User>('http://localhost:8000/auth').subscribe(data => {
      this.user = data;

      this.http.get('http://localhost:8000/user').subscribe(output => {
        console.log(output);
        this.fields = output;
      });
    });
}
}
