import { useEffect, useRef, useState } from 'react';
import React, { Component } from 'react';
import frameLT from './static/img/frame_LT.png';
import frameRT from './static/img/frame_RT.png';
import frameLB from './static/img/frame_LB.png';
import frameRB from './static/img/frame_RB.png';
import './App.css';
import TextField from './ui/TextField';
import TitleColored from './ui/TitleColored';
import * as apiService from './service/ApiService';

class App extends React.Component {
  sliding = false;

  state = {
    page1Class: "",
    page2Class: "slideIn",
    expression: ""
  };

  constructor(props) {
    super(props);
    this.textField = React.createRef();  
  }

  handleClick = (e) => {
    e.preventDefault();
    if (this.sliding) return;
    this.sliding = true;
    this.setState({ page2Class: 'slideIn', page1Class: "fadeOut" }, () => {
      this.textField.current.reset();
    });
  };

  handleStartGame = (e, level) => {
    e.preventDefault();

    apiService.get(level).then(response => {
      console.log(response.data);
      this.setState({
        ...this.state,
        expression: response.data
      }, () => {

        this.setState({ page2Class: 'slidIn slideOut', page1Class: "fadeIn" }, () => {
          setTimeout(() => {
            this.setState({ page2Class: '' }, () => {
              this.sliding = false;
            });
          }, 600)
        });

      })


    })
  };

  render() {
    const { page2Class, page1Class } = this.state;

    return (
      <div className="Game">
        <div className="Container">

          <div className="Frame">

            <div className="frameT frame" />
            <div className="frameB frame" />
            <div className="frameL frame" />
            <div className="frameR frame" />

            <img className="frameLT frame" src={frameLT} />
            <img className="frameRT frame" src={frameRT} />
            <img className="frameLB frame" src={frameLB} />
            <img className="frameRB frame" src={frameRB} />

            <div className="Board">

            </div>

            <div className="UI">
              <div className={`page1 ${page1Class}`}>
                <div className="buttonReturn" onClick={this.handleClick}>
                  X
                    </div>
                <div className='wrapper'>
                  <div className="expression">
                    {this.state.expression}
                  </div>
                  <div className="row clearfix">
                    <TextField ref={this.textField} expression={this.state.expression}/>
                  </div>
                </div>
              </div>

              <div className={`page2 ${page2Class}`}>
                <div className='wrapper'>
                  <TitleColored title="Fun Arithmetics" />
                  <div className="underline"></div>
                  <div className="row clearfix">
                    <div className="button w20 fltLeft" onClick={(e)=>this.handleStartGame(e, 1)}>
                      Level 1
                      </div>
                    <div className="button w20 fltLeft" onClick={(e)=>this.handleStartGame(e, 2)}>
                      Level 2
                      </div>
                    <div className="button w20 fltLeft" onClick={(e)=>this.handleStartGame(e, 3)}>
                      Level 3
                      </div>
                    <div className="button w20 fltLeft" onClick={(e)=>this.handleStartGame(e, 4)}>
                      Level 4
                      </div>
                    <div className="button w20 fltLeft" onClick={(e)=>this.handleStartGame(e, 5)}>
                      Level 5
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }
}

export default App;


