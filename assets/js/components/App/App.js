import React, { Component } from 'react'

import Navbar from '../Navbar/Navbar'
import Sidebar from '../Sidebar/Sidebar'
import Board from '../Board/Board'
import Toast from '../Toast/Toast'

class App extends Component {
    constructor(props) {
        super(props)

        this.state = {
            user: 'Guest',
            activity: [
                { id: 1, user: 'John', content: 'has changed the title of Column to Todo' },
                { id: 2, user: 'Guest', content: 'has updated Tests' }
            ],
            toast: { message: '', type: 'success' }
        }
    }
    
    recordActivity = type => {
        const activity = { id: this.state.activity.length + 1, user: this.state.user, content: type }

        this.setState({ activity: [...this.state.activity, activity] })
    }

    showToast = (message, type = 'success') => {
        this.setState({ toast : { message, type } })
    }

    render() {
        return (
            <div className="c-app">
                <Navbar />
                <main>
                    <Board board={this.props.board} recordActivity={this.recordActivity} showToast={this.showToast} />
                    <Sidebar activity={this.state.activity} />
                </main>
                <Toast toast={this.state.toast} />
            </div>
        )
    }
}

export default App
