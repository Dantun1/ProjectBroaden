import numpy as np
import tensorflow as tf
from tensorflow import keras
import pandas as pd
import mysql.connector
# Collaborative filtering model to predict what articles the user would like.

def calculate_rating(user_id,snippet_id):
    """
    Function takes in a specific user id and snippet id. Returns a value out of 5 based on how much the user liked the article.
    """
    conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database=""
    )
    cursor = conn.cursor()
    cursor.execute(f'SELECT "liked" AS interaction FROM userlikes WHERE userlikes.userId = {user_id} AND userlikes.snippetId = {snippet_id} UNION ALL SELECT "bookmarked" AS interaction FROM userbookmarks WHERE userbookmarks.userId = {user_id} AND userbookmarks.snippetId = {snippet_id} UNION ALL SELECT "commented" AS interaction FROM comments WHERE comments.userId = {user_id} AND comments.snippetId = {snippet_id} UNION ALL SELECT "seen" AS interaction FROM userseen WHERE userseen.userId = {user_id} AND userseen.snippetId = {snippet_id}')
    raw_interactions = cursor.fetchall()
    interactions = [interaction[0] for interaction in raw_interactions]

    user_rating = 0.0

    if 'liked' in interactions:
        user_rating +=1.5
    elif 'bookmarked' in interactions:
        user_rating +=1

    num_comments = sum(interaction == 'commented' for interaction in interactions)
    
    if num_comments == 0:
        user_rating += 0
    elif num_comments == 1:
        user_rating += 1.5
    elif num_comments <=3:
        user_rating += 2
    else: 
        user_rating += 3.5

    if 'seen' in interactions and user_rating == 0:
        user_rating +=0.1
    
    


    return user_rating

def compute_ratings_matrix():
    """
    Function assembles a matrix of i users and j snippets. It populates it with ratings based on how the user has interacted with the article.
    Also returns a binary version of that matrix where 1 represents a rated matrix and 0 represents a non-rated matrix.
    """
    conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database=""
)
    cursor = conn.cursor()
    cursor.execute('SELECT users.usersId, snippets.snippetId FROM users CROSS JOIN snippets;')
    
    users_snippets = cursor.fetchall()
    user_ids = list(dict.fromkeys([pair[0] for pair in users_snippets]))
    snippet_ids = list(dict.fromkeys([pair[1] for pair in users_snippets]))

    ratings_matrix = np.zeros((len(user_ids),len(snippet_ids)), dtype = float)
    binary_matrix = np.zeros((len(user_ids),len(snippet_ids)), dtype = int)

    for combination in users_snippets:
        user_rating = calculate_rating(combination[0],combination[1])
        ratings_matrix[user_ids.index(combination[0]),snippet_ids.index(combination[1])] = user_rating
        if user_rating > 0:
            binary_matrix[user_ids.index(combination[0]),snippet_ids.index(combination[1])] = 1

    return ratings_matrix, binary_matrix, user_ids, snippet_ids

def normalise_ratings(ratings, binary):

    ratings_mean = (np.sum(ratings*binary,axis=1)/(np.sum(binary, axis=1)+1e-12)).reshape(-1,1)
    ratings_norm = ratings - np.multiply(ratings_mean, binary) 

    return(ratings_norm, ratings_mean)



def cost_func_v(X,W,b, Y, R, lambda_):
    """
    Function is the cost function for the collaborative filtering model. 
    X -> Matrix of Snippets x Snippet features
    W -> Matrix of Users x User features
    b -> Matrix of bias values of len(users)
    Y -> Actual ratings matrix based on user interactions
    R-> Binary version of Y which consists of 1s and 0s where a user has seen an article.
    """
    j= (tf.linalg.matmul(X, tf.transpose(W)) + tf.transpose(b) - Y)*R
    J = 0.5 * tf.reduce_sum(j**2) + (lambda_/2) * (tf.reduce_sum(X**2) + tf.reduce_sum(W**2))
    return J


def compute_user_predictions(user_id):
    """
    Function generates the predicted ratings of all snippets for every user and returns a list of IDs of the highest predicted snippets for a given user.
    """
    conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database=""
    )
    ratings, binary, user_ids, snippet_ids = compute_ratings_matrix()
    ratings_mean, ratings_norm = normalise_ratings(ratings,binary)

    num_users, num_snippets = ratings.shape
    
    num_features = 4

    tf.random.set_seed(1234)

    user_features = tf.Variable(tf.random.normal((num_users,  num_features),dtype=tf.float64),  name='W')
    snippet_features = tf.Variable(tf.random.normal((num_snippets, num_features),dtype=tf.float64),  name='X')
    bias = tf.Variable(tf.random.normal((1, num_users),   dtype=tf.float64),  name='b')

    optimizer = keras.optimizers.Adam(learning_rate=1e-1)

    iterations = 80
    lambda_ = 1

    for i in range(iterations):
    
        with tf.GradientTape() as tape:
            cost = cost_func_v(user_features,snippet_features,bias, ratings_norm, binary, lambda_)
        
        gradients = tape.gradient(cost, [snippet_features,user_features, bias])

        optimizer.apply_gradients(zip(gradients, [snippet_features,user_features, bias]))

        if i % 20 == 0:
            print(f"{cost}is the cost for iteration {i}\n")

    predictions = np.transpose(np.matmul(snippet_features.numpy(),np.transpose(user_features.numpy())) + bias.numpy())+ ratings_mean

    user_predictions = predictions[user_ids.index(user_id)]
    unseen_recommendations = [user_predictions[i] for i in range(len(user_predictions)) if binary[user_ids.index(user_id)][i] == 0]
    snippet_predictions_dict = {snippet_ids[i]: user_predictions[i] for i in range(len(snippet_ids)) if user_predictions[i] in unseen_recommendations}

    ordered_user_predictions = [key[0] for key in sorted(snippet_predictions_dict.items(), key= lambda x:x[1])][::-1]

    print(ordered_user_predictions)

    return ordered_user_predictions






    
    
