import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FooterComponent } from '../footer/footer.component';

@Component({
  selector: 'app-content',
  standalone: true,
  imports: [CommonModule, FooterComponent],
  templateUrl: './content.component.html',
  styleUrls: ['./content.component.scss']
})
export class ContentComponent {
  showMore:Boolean = false;
  newTopic:Boolean = true;
  sendTopic:Boolean = false;
  showAuthors:Boolean = false;
  showAuthors2:Boolean = false;
  collapseHandler() {
    this.showMore = !this.showMore;
  }
  topicHandler(){
    this.newTopic = !this.newTopic;
  }
  sendTopicHandler(){
    this.sendTopic = !this.sendTopic;
    this.newTopic = !this.newTopic;
  }
  authorsHandler(){
    this.showAuthors = !this.showAuthors;
  }
  authorsHandler2(){
    this.showAuthors2 = !this.showAuthors2;
  }
}
