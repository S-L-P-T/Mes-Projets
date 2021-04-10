const Discord = require('discord.js'),
    client = new Discord.Client({
        fetchAllMembers: true,
        partials: ['MESSAGE', 'REACTION'],
        fetchAllMembers: true
    }),
    config = require('./config.json'),
    fs = require('fs'),
    humanizeDuration = require('humanize-duration'),
    cooldown = new Set()

client.login(config.token)
client.commands = new Discord.Collection()
client.db = require('./db.json')

fs.readdir('./commands', (err, files) => {
    if (err) throw err
    files.forEach(file => {
        if (!file.endsWith('.js')) return
        const command = require(`./commands/${file}`)
        client.commands.set(command.name, command)
    })
})

client.on('message', message => {
    if (message.type !== 'DEFAULT' || message.author.bot) return

    if (message.guild) {
        if (!message.member.hasPermission('MANAGE_CHANNELS') && client.db.lockedChannels.includes(message.channel.id)) return message.delete()

        if (!message.member.hasPermission('MANAGE_MESSAGES')) {
            const duration = config.cooldown[message.channel.id]
            if (duration) {
                const id = `${message.channel.id}_${message.author.id}`
                if (cooldown.has(id)) {
                    message.delete()
                    return message.channel.send(`Ce salon est soumis a un cooldown de ${humanizeDuration(duration, { language: 'fr' })}.`).then(sent => sent.delete({ timeout: 5e3 }))
                }
                cooldown.add(id)
                setTimeout(() => cooldown.delete(id), duration)
            }
        }
    }

    const args = message.content.trim().split(/ +/g)
    const commandName = args.shift().toLowerCase()
    if (!commandName.startsWith(config.prefix)) return
    const command = client.commands.get(commandName.slice(config.prefix.length))
    if (!command) return
    if (command.guildOnly && !message.guild) return message.channel.send('Cette commande ne peut être utilisée que dans un serveur.').then(sent => sent.delete({ timeout: 5e3 }))
    command.run(message, args, client)
    if (command === client.commands.get(commandName.clear)) return
    if (command === client.commands.get(commandName.clear)) return
    else
        message.delete()
})

client.on('ready', () => {
    const statuses = [
        'Besoin d\'aide: _______!help_______\n Downloading:┣──────────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣█─────────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣██────────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣███───────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣████──────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣█████─────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣██████────┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣███████───┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣████████──┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣█████████─┫',
        'Besoin d\'aide: _______!help_______\n Downloading:┣██████████┫'
    ]
    let i = 0
    setInterval(() => {
        client.user.setActivity(statuses[i], { type: 'PLAYING' })
        i = ++i % statuses.length
    }, 4e3)
    setInterval(() => {
        const [bots, humans] = client.guilds.cache.first().members.cache.partition(member => member.user.bot)
        client.channels.cache.get(config.serverStats.humans).setName(`🧠 Membres : ${humans.size}`)
        client.channels.cache.get(config.serverStats.bots).setName(`🤖 Bots : ${bots.size}`)
        client.channels.cache.get(config.serverStats.total).setName(`👬 Total : ${client.guilds.cache.first().memberCount}`)
    }, 3e4)
})

client.on('messageReactionAdd', (reaction, user) => {
    if (!reaction.message.guild || user.bot) return
    const reactionRoleElem = config.reactionRole[reaction.message.id]
    if (!reactionRoleElem) return
    const prop = reaction.emoji.id ? 'id' : 'name'
    const emoji = reactionRoleElem.emojis.find(emoji => emoji[prop] === reaction.emoji[prop])
    if (emoji) reaction.message.guild.member(user).roles.add(emoji.roles)
    else reaction.users.remove(user)
})

client.on('messageReactionRemove', (reaction, user) => {
    if (!reaction.message.guild || user.bot) return
    const reactionRoleElem = config.reactionRole[reaction.message.id]
    if (!reactionRoleElem || !reactionRoleElem.removable) return
    const prop = reaction.emoji.id ? 'id' : 'name'
    const emoji = reactionRoleElem.emojis.find(emoji => emoji[prop] === reaction.emoji[prop])
    if (emoji) reaction.message.guild.member(user).roles.remove(emoji.roles)
})

client.on('channelCreate', channel => {
    if (!channel.guild) return
    const muteRole = channel.guild.roles.cache.find(role => role.name === 'Muted')
    if (!muteRole) return
    channel.createOverwrite(muteRole, {
        SEND_MESSAGES: false,
        CONNECT: false,
        ADD_REACTIONS: false,
        CREATE_INSTANT_INVITE: false
    })
})

const canvacord = require("canvacord");

client.on("message", async (message) => {
    if (message.content === `!triggered`) {
        message.delete()
        const member = message.mentions.members.first() || message.member
        let avatar = member.user.displayAvatarURL({ dynamic: false, format: 'png' });
        let image = await canvacord.Canvas.trigger(avatar);
        let attachment = new Discord.MessageAttachment(image, "triggered.gif");
        return message.channel.send(attachment);
    }
});



/*client.on('guildMemberAdd', async member => {
    const { Welcomer } = require("canvacord");
    
    const card = new Welcomer()
        .setBackground('./wallpaper.jpg')
        .setUsername(member.user.username)
        .setDiscriminator(member.user.discriminator)
        .setMemberCount(member.guild.memberCount.toLocaleString())
        .setGuildName(member.guild.name)
        .setAvatar(member.user.displayAvatarURL())
        .setColor("border", "#eb26dd")
        .setColor("username-box", "#eb26dd")
        .setColor("discriminator-box", "#eb26dd")
        .setColor("message-box", "#eb26dd")
        .setColor("title", "#eb26dd")
        .setColor("avatar", "#eb26dd")
        .setText("member-count", "- {count} MEMBRES !")
        .setText("title", "bienvenue,")
        .setText("message", "sur mon serveur !")
    
    
    card.build()
        .then(buffer => client.channels.cache.get(config.greeting.channel).send(new Discord.MessageAttachment(buffer, "welcome.png"))
    );
});*/

const applyText = (canvas, text) => {
    const ctx = canvas.getContext('2d');
    let fontSize = 70;

    do {
        ctx.font = `${fontSize -= 8}px sans-serif`;
    } while (ctx.measureText(text).width > canvas.width - 200);

    return ctx.font;
};

/*const applyText = ( text, ctx, maxWidth ) => {
    if (!text) return [];
    if (!ctx) throw new Error("Canvas context was not provided!");
    if (!maxWidth) throw new Error("No max-width provided!");
    const lines = [];

    while (text.length) {
        let i;
        for (i = text.length; ctx.measureText(text.substr(0, i)).width > maxWidth; i -= 1);
        const result = text.substr(0, i);
        let j;
        if (i !== text.length) for (j = 0; result.indexOf(" ", j) !== -1; j = result.indexOf(" ", j) + 1);
        lines.push(result.substr(0, j || result.length));
        text = text.substr(lines[lines.length - 1].length, text.length);
    }

    return lines;
}*/

const Canvas = require('canvas');
client.on("guildMemberAdd", async member => {

    const canvas = Canvas.createCanvas(550, 250)
    const ctx = canvas.getContext("2d")

    const background = await Canvas.loadImage("./images/wallpaper.jpg")
    ctx.drawImage(background, 0, 0, canvas.width, canvas.height)

    //ctx.fillStyle = "rgba(0, 0, 255, 0.8)"
    //ctx.fillRect(20, 30, 500, 200)

    //    ctx.strokeStyle = "#303c48"
    //    ctx.strokeRect(0, 0, canvas.width, canvas.height)

    // Texte générique d'introduction avant le nom d'utilisateur
    ctx.textAlign = "start"
    ctx.font = "45px sans-serif"
    ctx.fillStyle = "#ffffff"
    ctx.fillText(
        "Bienvenue,",
        canvas.width / 2.4,
        canvas.height / 3.4
    )

    // Ajouter le nom d'utilisateur
    ctx.font = applyText(canvas, `${member.displayName}`)
    ctx.fillStyle = "#ffffff"
    ctx.textAlign = "center"
    ctx.textBaseline = 'middle'
    ctx.fillText(
        `${member.displayName} !`,
        canvas.width / 1.55,
        canvas.height / 2
    )

    ctx.textAlign = "start"
    ctx.font = "25px sans-serif"
    ctx.fillStyle = "#ffffff"
    ctx.fillText(
        `Nous sommes désormais ${member.guild.memberCount}`,
        canvas.width / 3.1,
        canvas.height / 1.3
    )
    ctx.beginPath()
    ctx.fillStyle = "#7289da"
    ctx.arc(100, 125, 75, 0, Math.PI * 2)
    ctx.fill()

    ctx.beginPath()
    ctx.arc(100, 125, 69.5, 0, Math.PI * 2, true)
    ctx.closePath()
    ctx.clip()



    //    ctx.strokeStyle = "#800"
    //    ctx.lineWidth = 5,
    //    ctx.strokeRect(25, 25, 200, 200)

    const avatar = await Canvas.loadImage(
        member.user.displayAvatarURL({ format: "jpg" })
    )
    ctx.drawImage(avatar, 25, 50, 150, 150)

    const attachment = new Discord.MessageAttachment(
        canvas.toBuffer(),
        "bienvenue-image.png"
    )

    member.guild.channels.cache.get(config.greeting.channel).send(new Discord.MessageEmbed()
        .setDescription(`**Bienvenue sur le serveur, ${member} !\nNous sommes désormais ${member.guild.memberCount} ! 🎉**`)
        .setColor('RANDOM'))

    member.guild.channels.cache.get(config.greeting.channel).send(attachment)
})



/*const Canvas = require('canvas');
client.on('guildMemberAdd', async member => {

    const canvas = Canvas.createCanvas(700, 250);
    const ctx = canvas.getContext('2d');

    const background = await Canvas.loadImage('./wallpaper.jpg');
    ctx.drawImage(background, 0, 0, canvas.width, canvas.height);

    ctx.strokeStyle = '#303c48';
    ctx.strokeRect(0, 0, canvas.width, canvas.height);

    // Slightly smaller text placed above the member's display name
    ctx.font = '28px sans-serif';
    ctx.fillStyle = '#ffffff';
    ctx.shadowBlur = 5;
    ctx.shadowColor = 'white';
    ctx.fillText(`Bienvenue sur mon server,\n \n \n \nNous sommes désormais ${member.guild.memberCount} !`, canvas.width / 3, canvas.height / 3.5);

    // Add an exclamation point here and below
    ctx.font = applyText(canvas, `${member.displayName}!`);
    ctx.fillStyle = '#ffffff';
    ctx.fillText(`${member.displayName}!`, canvas.width / 3, canvas.height / 1.7);

//  ctx.font = '28px sans-serif';
//	ctx.fillStyle = '#ffffff';
//	ctx.fillText(`Nous sommes désormais ${member.guild.memberCount} ! 🎉`, canvas.width / 2.5, canvas.height / 3.5);

    ctx.beginPath();
    ctx.arc(125, 125, 100, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.clip();

    const avatar = await Canvas.loadImage(member.user.displayAvatarURL({ format: 'jpg' }));
    ctx.drawImage(avatar, 25, 25, 200, 200);

    const attachment = new Discord.MessageAttachment(canvas.toBuffer(), 'welcome-image.png');

    member.guild.channels.cache.get(config.greeting.channel).send(`Bienvenue sur mon server, ${member}!\nNous sommes désormais ${member.guild.memberCount} ! 🎉`, attachment);

//  member.guild.channels.cache.get(config.greeting.channel).send(`${member} a rejoint le serveur. Nous sommes désormais ${member.guild.memberCount} ! 🎉`)
//  member.roles.add(config.greeting.role)
})*/

client.on('guildMemberRemove', member => {
    member.guild.channels.cache.get(config.greeting1.channel).send(new Discord.MessageEmbed()
        .setDescription(`${member.user.tag} a quitté le serveur... 😢`)
        .setColor('#ff0000')
    )
    delete client.db.captcha[member.id]
    delete client.db.ranking[member.id]
    fs.writeFileSync('./db.json', JSON.stringify(client.db))
})


/*const fs1 = require('fs').promises;
const createCaptcha = require('./captcha.js');

client.on('guildMemberAdd', async member => {
    const captcha = await createCaptcha();
    try {
        const msg = await member.send('Vous avez 60 secondes pour résoudre le captcha !', {
            files: [{
                attachment: `${__dirname}/captchas/${captcha}.png`,
                name: `${captcha}.png`
            }]
        });
        await fs1.unlink(`${__dirname}/captchas/${captcha}.png`)
        .catch(err => console.log(err));
        try {
            const filter = m => {
                if(m.author.bot) return;
                if(m.author.id === member.id && m.content === captcha) return true;
                else {
                    m.channel.send('Vous avez mal rentré le captcha !');
                    return false;
                }
            };
            const response = await msg.channel.awaitMessages(filter, { max: 1, time: 60000, errors: ['time']});
            if(response) {
                await msg.channel.send('Vous êtes maintenant vérifié !');
                await member.roles.add('822861834452992000');
                await fs1.unlink(`${__dirname}/captchas/${captcha}.png`)
                .catch(err => console.log(err));
            }
        }
        catch(err) {
            console.log(err);
            await msg.channel.send('Vous n\'avez pas résolu le captcha dans le temps imparti !');
            await member.kick();
            await fs1.unlink(`${__dirname}/captchas/${captcha}.png`)
            .catch(err => console.log(err));
        }
    }
    catch(err) {
        console.log(err);
    }
});*/






client.on("guildMemberAdd", async member => {
    
    const channel = await member.guild.channels.create(`captcha`, {
        type: 'text',
        parent: config.captcha.category,
        permissionOverwrites: [{
            id: member.guild.id,
            deny: 'VIEW_CHANNEL'
        }, {
            id: member.id,
            allow: ['SEND_MESSAGES', 'VIEW_CHANNEL']
        }]
    })
    fs.writeFileSync('./db.json', JSON.stringify(client.db))
    const captchatext = Math.random().toString(36).slice(2, 8)
    const { captcha } = client.db.captcha[member.id] = {
        captcha: captchatext,
    };
    fs.writeFileSync('./db.json', JSON.stringify(client.db))
    const canvas = Canvas.createCanvas(600, 200)
    const ctx = canvas.getContext("2d")

    const background = await Canvas.loadImage("./images/image011.jpg")
    ctx.drawImage(background, 0, 0, canvas.width, canvas.height)

    //ctx.fillStyle = "rgba(0, 0, 255, 0.8)"
    //ctx.fillRect(20, 30, 500, 200)

    //    ctx.strokeStyle = "#303c48"
    //    ctx.strokeRect(0, 0, canvas.width, canvas.height)

    // Texte générique d'introduction avant le nom d'utilisateur


    // Ajouter le nom d'utilisateur
    ctx.font = applyText(canvas, `${captchatext}`)
    ctx.fillStyle = "#000000"
    ctx.font = "100px sans-serif"
    ctx.textAlign = "center"
    ctx.textBaseline = 'middle'
    ctx.fillText(
        `${captchatext}`,
        canvas.width / 2,
        canvas.height / 2.2
    )


    const attachment = new Discord.MessageAttachment(
        canvas.toBuffer(),
        `${captchatext}.png`
    )



    try {
        const msg = await channel.send(new Discord.MessageEmbed()
            .setDescription(`**Bienvenue sur le serveur, ${member} !\n⬇️ Veuillez compléter le captcha ci-dessous ! ⬇️**`)
            .setColor('RANDOM'))
        channel.send(attachment)
        try {
            const filter = m => {
                if (m.author.bot) return;
                if (!m.author.bot) m.delete()
                if (m.author.id === member.id && m.content === captcha) return true;
                else {
                    m.channel.send(`${member}, vous avez mal rentré le captcha !`).then(sent => sent.delete({ timeout: 5e3 }))
                    return false;
                }
            };
            const response = await msg.channel.awaitMessages(filter, { max: 1, time: 60000, errors: ['time'] });
            if (response) {
                await msg.channel.send(`${member}, vous êtes maintenant vérifié !`).then(sent => sent.delete({ timeout: 5e3 }))
                await member.roles.add('822861834452992000')
                channel.delete()
                    .catch(err => console.log(err));
                client.db.captcha[member.id] = {
                    vérifié: true,
                };
                fs.writeFileSync('./db.json', JSON.stringify(client.db))
            }
        }
        catch (err) {
            console.log(err);
            await msg.channel.send(`${member}, vous n\'avez pas résolu le captcha dans le temps imparti !`).then(sent => sent.delete({ timeout: 5e3 }))
            delete client.db.captcha[member.id]
            fs.writeFileSync('./db.json', JSON.stringify(client.db))
            await member.kick()
            channel.delete()
                .catch(err => console.log(err));
        }
    }
    catch (err) {
        channel.delete()
        console.log(err);
    }
})












client.on("message", async (message, member) => {
    if (message.author.bot) return;
    if (message.guild.channels.cache.get(config.noxp.channels)) return;
    const args = message.content.trim().split(/ +/g)
    const commandName = args.shift().toLowerCase()
    if (commandName.startsWith(config.prefix)) return;
    if (!client.db.ranking[message.author.id]) client.db.ranking[message.author.id] = {
        xp: 0,
        level: 0
    };
    client.db.ranking[message.author.id].xp += Math.floor((Math.random() * 5) + 1);
    fs.writeFileSync('./db.json', JSON.stringify(client.db))
    let userInfo = client.db.ranking[message.author.id];
    if (userInfo.xp > 20) {
        userInfo.level++
        userInfo.xp = 0
        message.channel.send(new Discord.MessageEmbed()
            .setAuthor(
                `Bien joué ${message.author.username}`,
                message.author.displayAvatarURL()
            )
            .setTitle('Vous venez de passer de niveau !')
            .setThumbnail('https://i.imgur.com/lXeBiMs.png')
            .setColor('RANDOM')
            .addField(`Nouveau lvl`, `lvl: ${userInfo.level}`));
    }
    fs.writeFile("./database.json", JSON.stringify(client.db), (x) => {
        if (x) console.error(x)
    });
})





client.on("message", async (message, member) => {


if (message.content === "yo")
    message.channel.send(new Discord.MessageEmbed()
        .setTitle('**⚠️BAN⚠️**')
        .setDescription(`**${member} a été banni !**`)
        .setColor('#ff0000')
        .setAuthor(`${message.author.username}#${message.author.discriminator}`, 'https://cdn.discordapp.com/attachments/718476721418141728/719563110154764298/logo.png')
        .setFooter(`${message.author.username}`, message.author.displayAvatarURL())
        .setTimestamp()
)
})