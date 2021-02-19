import { useEffect, useState } from 'react';
import React, { Component } from 'react';
import frameLT from './static/img/frame_LT.png';
import frameRT from './static/img/frame_RT.png';
import frameLB from './static/img/frame_LB.png';
import frameRB from './static/img/frame_RB.png';
import frameT from './static/img/frame_T.png';
import frameB from './static/img/frame_B.png';
import frameL from './static/img/frame_L.png';
import frameR from './static/img/frame_R.png';
import './App.css';
import axios from 'axios';


import { bounce, tada } from 'react-animations';
import Radium, { StyleRoot } from 'radium';

class App extends React.Component {
  sliding = false;
  state = {
    page2Class: "slideIn",
  };

  handleClick = () => {
    if (this.sliding) return;
    this.sliding = true;
    this.setState({ page2Class: 'slideIn' }, () => {

    });
  };

  handleClose = () => {
    this.setState({ page2Class: 'slidIn slideOut' }, () => {
      setTimeout(() => {
        this.setState({ page2Class: '' }, () => {
          this.sliding = false;
        });
      }, 600)
    });
  };

  render() {
    const { page2Class } = this.state;
    return (
      <div className="Game">
        <div className="Container">

          <div className="Frame">

            <img className="frameT frame" src={frameT} />
            <img className="frameB frame" src={frameB} />
            <img className="frameL frame" src={frameL} />
            <img className="frameR frame" src={frameR} />

            <img className="frameLT frame" src={frameLT} />
            <img className="frameRT frame" src={frameRT} />
            <img className="frameLB frame" src={frameLB} />
            <img className="frameRB frame" src={frameRB} />

            <div className="Board">

            </div>

            {/* <div className="expression">
              {expression}  
            </div>       */}

            {/* <div className="solution">
              <input/>
            </div> */}

            <div className="UI">
                <div className='page1' onClick={this.handleClick}>
                    <div className='wrapper'>
                    <h1 className="title">Fun Arithmetics</h1>
                    </div>
                </div>

                <div className={`page2 ${page2Class}`}>
                  <div className='wrapper'>
                    <h1 className="title">Fun Arithmetics</h1>
                    <div className="underline"></div>
                    <div className="buttons">
                      <div className="button" onClick={this.handleClose}>
                        Level 1
                      </div>
                      <div className="button" onClick={this.handleClose}>
                        Level 2
                      </div>
                      <div className="button" onClick={this.handleClose}>
                        Level 3
                      </div>
                      <div className="button" onClick={this.handleClose}>
                        Level 4
                      </div>
                      <div className="button" onClick={this.handleClose}>
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