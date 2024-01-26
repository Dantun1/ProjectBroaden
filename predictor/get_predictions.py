from flask import Flask, request, jsonify
import recommender

api = Flask(__name__)

@api.route('/')
def home():
    return "ayo"


@api.route('/get_predictions', methods = ['GET'])
def prepare_predictions():

    user_id = int(request.args.get('user_id'))

    predictions = recommender.compute_user_predictions(user_id)

    return jsonify(predictions)

if __name__ == '__main__':
    api.run(port=8080)