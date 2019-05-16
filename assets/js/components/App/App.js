import React, { Component } from 'react'
import Axios from 'axios'

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

    componentDidMount() {
        this.getActivity()
    }

    getActivity = () => {
        Axios.get('/board/' + this.props.board.id + '/activity')
        .then(response => {
            this.setState({ activity: JSON.parse(response.data) })
        })
        .catch(error => {
            console.log(error)
        })
    }
    
    recordActivity = (entity_type, entity_id, action) => {
        const data = {
            entity_type,
            entity_id,
            action,
            user_id: this.props.user,
        }

        Axios.post('/board/' + this.props.board.id + '/activity', data)
        .then(response => {
            this.setState({ activity: [JSON.parse(response.data), ...this.state.activity] })
        })
        .catch(error => {
            console.log(error)
        })
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
