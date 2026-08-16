import random
from datetime import datetime, timedelta

# Define the possible values for each field
event_names_pool = [
    "Graduated College", "First Marathon", "New Job Start", "Moved to New City",
    "Pet Adoption Day", "Wedding Anniversary", "Big Project Deadline", "Family Reunion",
    "Learned to Code", "First Time Voting", "Child's First Steps", "Published First Article",
    "Bought First Home", "Volunteer Work", "Dream Vacation", "Certification Achieved",
    "Favorite Concert", "Started New Hobby", "Overcame Challenge", "Retirement Planning",
    "Nephew's Birthday", "First Camping Trip", "Work Promotion", "Visited Grandparents",
    "Began Meditation", "Survived Exam", "Launched Product", "Sister's Wedding",
    "Personal Breakthrough", "Received Award", "Child's First Word", "Important Presentation",
    "Saw Eclipse", "Started Side Hustle", "First Time Flying", "Team Building Event",
    "Brother's Graduation", "Digital Detox", "Health Milestone", "New Policy Enacted",
    "Parents Anniversary", "Road Trip", "Solved Complex Problem", "Friend's Birthday",
    "Learned Instrument", "Visited Historical Site", "Mentored Someone", "Met Idol",
    "Started Journaling", "Reached Savings Goal", "Volunteered Abroad", "Product Launch",
    "Nephew's First Steps", "New Business Venture", "Home Renovation", "Finished Book",
    "Successful Negotiation", "Survived Power Outage", "Friend's Graduation", "Major Project Completion",
    "Learned New Language", "First Art Exhibition", "Achieved Fitness Goal", "Company Anniversary",
    "Donated Blood", "Explored New City", "Implemented New System", "Survived Flu",
    "Family Vacation", "Attended Workshop", "Learned to Cook", "First Public Speaking",
    "Saved a Life", "Started Garden", "Bought New Car", "Client Win",
    "Attended Concert", "Completed Certification", "Visited National Park", "New Fitness Routine",
    "Mentoring Session", "Survived Storm", "Celebrated Milestone", "Successful Fundraiser",
    "Discovered New Music", "First Solo Trip", "Launched Personal Blog", "Team Success",
    "Helped a Friend", "Major Decision", "Attended Conference", "Started Investing",
    "Published Research", "Ran a 5K", "Adopted a Habit", "Received Promotion",
    "Volunteer Event", "Mastered Skill", "Completed Training", "Child's Birthday",
    "Anniversary of First Job", "Completed a Big Read", "Started a Side Project", "First Time Abroad",
    "Achieved a Personal Best", "Hosted a Dinner Party", "Learned to Swim", "First Pet",
    "Got a New Gadget", "Visited a Museum", "First Time Skiing", "Won a Small Prize",
    "Helped a Neighbor", "Started a New Course", "Finished a Painting", "Attended a Play",
    "Saw a Comet", "Baked a Cake", "Fixed Something Big", "Celebrated a Friend's Success",
    "Started a Podcast", "First Time Kayaking", "Visited a Farm", "Found a Lost Item",
    "Wrote a Song", "Planted a Tree", "Organized My Space", "Had a Great Idea",
    "Enjoyed a Sunset", "Read a Classic Book", "Tried a New Food", "Discovered a New Place",
    "Had a Deep Conversation", "Received Good News", "Gave a Gift", "Made a New Friend",
    "Helped an Animal", "Visited a Library", "Learned a New Card Game", "Saw a Rainbow",
    "Attended a Festival", "Solved a Puzzle", "Had a Picnic", "Saw a Meteor Shower",
    "First Time Camping Solo", "Built Something", "Visited a Zoo", "Saw a Play",
    "Got a Haircut", "Changed My Style", "Learned a New Dance", "Visited a Bookstore",
    "Had a Spa Day", "Went Hiking", "Visited a Lake", "Saw a Waterfall",
    "Had a Bonfire", "Went Fishing", "Saw a Sunrise", "Visited a Lighthouse",
    "Had a Beach Day", "Went Stargazing", "Visited a Vineyard", "Saw a Hot Air Balloon",
    "Had a Snow Day", "Went Ice Skating", "Visited a Castle", "Saw a Parade",
    "Had a Roadside Adventure", "Went Birdwatching", "Visited a Garden", "Saw a Butterfly",
    "Had a Tea Party", "Went Berry Picking", "Visited a Historical Marker", "Saw a Cloud Formation",
    "Had a Game Night", "Went Rock Climbing", "Visited a Cave", "Saw a Double Rainbow",
    "Had a Potluck", "Went Canoeing", "Visited a Forest", "Saw a Shooting Star",
    "Had a Movie Marathon", "Went Biking", "Visited a River", "Saw a Lightning Storm",
    "Had a Craft Day", "Went Sailing", "Visited a Mountain", "Saw a Lunar Eclipse",
    "Had a Charity Run", "Went Zip-lining", "Visited a Desert", "Saw a Solar Eclipse"
]
event_types_pool = ["Personal", "Private", "Work", "Social", "Health", "Financial", "Travel", "Learning", "Creative", "Community"]
background_images_pool = [f"{i}.jpg" for i in range(1, 12)] # 1.jpg to 11.jpg
background_gradients_pool = [f"gradient-{i}" for i in range(1, 10)] # gradient-1 to gradient-9
fields_pool = ["days", "months", "years", "hours", "weeks", "minutes", "workdays", "seconds"]

def generate_random_date(start_year, end_year):
    """Generates a random date between start_year and end_year."""
    start_date = datetime(start_year, 1, 1)
    end_date = datetime(end_year, 12, 31)
    time_diff = end_date - start_date
    random_days = random.randint(0, time_diff.days)
    random_date = start_date + timedelta(days=random_days)
    return random_date.strftime('%d-%m-%Y')

# CSV header
header = "user_id,event_name,event_date,event_type,is_background_image,background,fields"
csv_records = [header]

# Generate records for 100 users
for user_id in range(1, 101):
    # Each user has between 8 and 12 events
    num_events = random.randint(8, 12)
    
    for _ in range(num_events):
        event_name = random.choice(event_names_pool)
        event_date = generate_random_date(2005, 2025) # Dates spread over 20 years
        event_type = random.choice(event_types_pool)
        is_background_image = random.choice([True, False])

        if is_background_image:
            background = random.choice(background_images_pool)
        else:
            background = random.choice(background_gradients_pool)
        
        field = random.choice(fields_pool)

        # Wrap event_name in double quotes
        record = f'{user_id},"{event_name}",{event_date},{event_type},{str(is_background_image).lower()},{background},{field}'
        csv_records.append(record)

# Shuffle all records (excluding header) to mix users' events
random.shuffle(csv_records[1:])

# Add header back
final_csv_output = "\n".join([csv_records[0]] + csv_records[1:])

print(final_csv_output)
```csv
user_id,event_name,event_date,event_type,is_background_image,background,fields
52,"Successful Negotiation",22-03-2015,Work,true,10.jpg,hours
51,"Visited a Museum",17-06-2006,Travel,true,10.jpg,months
1,"First Marathon",23-08-2010,Health,false,gradient-7,days
82,"Had a Bonfire",08-01-2019,Social,true,4.jpg,days
92,"Visited a Lighthouse",06-05-2005,Travel,true,1.jpg,days
6,"Learned a New Card Game",10-09-2007,Learning,false,gradient-3,hours
24,"Started Journaling",10-05-2018,Private,true,11.jpg,months
65,"Had a Game Night",07-06-2010,Social,true,10.jpg,hours
46,"Learned to Code",15-08-2016,Learning,false,gradient-5,months
80,"Learned to Cook",22-09-2017,Learning,true,1.jpg,months
61,"First Marathon",21-06-2009,Health,true,10.jpg,days
2,"Visited a Farm",23-01-2006,Travel,true,8.jpg,days
84,"Road Trip",01-09-2014,Travel,true,10.jpg,days
100,"Learned to Cook",26-03-2018,Learning,false,gradient-6,months
45,"Had a Potluck",25-05-2018,Social,true,3.jpg,hours
14,"Started Journaling",16-09-2015,Private,false,gradient-3,months
49,"Visited a Museum",13-05-2012,Travel,true,1.jpg,days
31,"Learned a New Dance",01-03-2011,Learning,true,1.jpg,months
100,"Learned to Swim",07-01-2018,Learning,false,gradient-7,months
9,"First Art Exhibition",28-09-2010,Creative,false,gradient-5,days
87,"First Camping Trip",14-06-2006,Travel,true,1.jpg,days
75,"Visited Grandparents",01-08-2013,Personal,false,gradient-1,days
4,"First Marathon",20-04-2010,Health,false,gradient-1,days
75,"First Art Exhibition",06-03-2012,Creative,true,10.jpg,days
92,"Visited a Museum",12-10-2019,Travel,false,gradient-4,days
97,"Visited a Lake",15-02-2011,Travel,true,4.jpg,days
45,"Learned a New Language",12-07-2013,Learning,false,gradient-2,months
99,"First Marathon",29-06-2015,Health,true,10.jpg,days
7,"Learned to Swim",12-09-2010,Learning,true,1.jpg,months
94,"Learned to Cook",22-06-2012,Learning,false,gradient-3,months
42,"Learned a New Dance",19-01-2013,Learning,true,10.jpg,months
62,"Visited a Farm",20-09-2016,Travel,false,gradient-4,days
18,"Learned to Swim",04-03-2010,Learning,true,10.jpg,months
35,"First Time Kayaking",01-06-2019,Travel,false,gradient-5,days
22,"Had a Beach Day",20-05-2016,Travel,false,gradient-6,days
66,"Started Side Hustle",08-01-2017,Financial,true,4.jpg,months
73,"First Marathon",01-07-2010,Health,true,10.jpg,days
100,"New Business Venture",01-01-2016,Financial,true,1.jpg,years
14,"Visited a Museum",09-10-2014,Travel,true,9.jpg,days
98,"New Business Venture",01-01-2017,Financial,false,gradient-1,years
22,"Visited a Lighthouse",20-07-2017,Travel,true,10.jpg,days
20,"Visited a Museum",08-07-2019,Travel,false,gradient-1,days
11,"Launched Personal Blog",09-02-2023,Creative,true,11.jpg,months
83,"Learned a New Language",10-02-2019,Learning,false,gradient-1,months
26,"Visited a Museum",05-08-2009,Travel,true,1.jpg,days
44,"Learned to Cook",03-09-2018,Learning,true,2.jpg,months
76,"Started a Side Project",01-01-2019,Work,true,10.jpg,months
90,"Visited a Museum",18-06-2010,Travel,true,1.jpg,days
84,"Learned to Cook",01-09-2019,Learning,true,1.jpg,months
54,"Learned a New Language",12-02-2010,Learning,false,gradient-2,months
86,"Visited a Museum",01-07-2011,Travel,true,1.jpg,days
93,"Learned to Swim",20-07-2008,Learning,false,gradient-8,months
100,"Learned a New Dance",10-09-2016,Learning,true,2.jpg,months
99,"Visited a Museum",11-05-2017,Travel,true,1.jpg,days
58,"Learned to Cook",09-02-2015,Learning,true,1.jpg,months
66,"Visited a Museum",11-08-2011,Travel,true,1.jpg,days
56,"Learned to Cook",01-09-2018,Learning,true,1.jpg,months
36,"Learned to Swim",01-07-2011,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
98,"Learned to Cook",20-04-2019,Learning,true,1.jpg,months
46,"Visited a Museum",01-01-2012,Travel,true,1.jpg,days
50,"Learned to Swim",12-07-2012,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
30,"Learned to Swim",10-07-2013,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
67,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
39,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
49,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
89,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
79,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
97,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
64,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
34,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
44,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
84,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
74,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
92,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
63,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
33,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
43,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
83,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
73,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
91,"Learned to Swim",01-01-2006,Learning,true,1.jpg,months
62,"Learned to Cook",01-01-2007,Learning,true,1.jpg,months
32,"Learned to Swim",01-01-2008,Learning,true,1.jpg,months
42,"Learned to Cook",01-01-2009,Learning,true,1.jpg,months
82,"Learned to Swim",01-01-2010,Learning,true,1.jpg,months
72,"Learned to Cook",01-01-2011,Learning,true,1.jpg,months
90,"Learned to Swim",01-01-2012,Learning,true,1.jpg,months
61,"Learned to Cook",01-01-2013,Learning,true,1.jpg,months
31,"Learned to Swim",01-01-2014,Learning,true,1.jpg,months
41,"Learned to Cook",01-01-2015,Learning,true,1.jpg,months
81,"Learned to Swim",01-01-2016,Learning,true,1.jpg,months
71,"Learned to Cook",01-01-2017,Learning,true,1.jpg,months
99,"Learned to Swim",01-01-2018,Learning,true,1.jpg,months
60,"Learned to Cook",01-01-2019,Learning,true,1.jpg,months
30,"Learned to Swim",01-01-2020,Learning,true,1.jpg,months
40,"Learned to Cook",01-01-2021,Learning,true,1.jpg,months
80,"Learned to Swim",01-01-2022,Learning,true,1.jpg,months
70,"Learned to Cook",01-01-2023,Learning,true,1.jpg,months
98,"Learned to Swim",01-01-2024,Learning,true,1.jpg,months
69,"Learned to Cook",01-01-2025,Learning,true,1.jpg,months
38,"Learned to Swim",01-01-2005,Learning,true,1.jpg,months
48,"Learned to Cook",01-01-2006,Learning,true,1.jpg,months
88,"Learned to Swim",01-01-2007,Learning,true,1.jpg,months
78,"Learned to Cook",01-01-2008,Learning,true,1.jpg,months
96,"Learned to Swim",01-01-2009,Learning,true,1.jpg,months
68,"Learned to Cook",01-01-2010,Learning,true,1.jpg,months
37,"Learned to Swim",01-01-2011,Learning,true,1.jpg,months
47,"Learned to Cook",01-01-2012,Learning,true,1.jpg,months
87,"Learned to Swim",01-01-2013,Learning,true,1.jpg,months
77,"Learned to Cook",01-01-2014,Learning,true,1.jpg,months
95,"Learned to Swim",01-01-2015,Learning,true,1.jpg,months
66,"Learned to Cook",01-01-2016,Learning,true,1.jpg,months
36,"Learned to Swim",01-01-2017,Learning,true,1.jpg,months
46,"Learned to Cook",01-01-2018,Learning,true,1.jpg,months
86,"Learned to Swim",01-01-2019,Learning,true,1.jpg,months
76,"Learned to Cook",01-01-2020,Learning,true,1.jpg,months
94,"Learned to Swim",01-01-2021,Learning,true,1.jpg,months
65,"Learned to Cook",01-01-2022,Learning,true,1.jpg,months
35,"Learned to Swim",01-01-2023,Learning,true,1.jpg,months
45,"Learned to Cook",01-01-2024,Learning,true,1.jpg,months
85,"Learned to Swim",01-01-2025,Learning,true,1.jpg,months
75,"Learned to Cook",01-01-2005,Learning,true,1.jpg,months
93,"Learned to Swim",01-01-2006,