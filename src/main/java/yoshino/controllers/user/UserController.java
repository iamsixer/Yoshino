package yoshino.controllers.user;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import yoshino.engine.StreamEngine;
import yoshino.models.Channel;
import yoshino.models.User;
import yoshino.services.ChannelService;
import yoshino.services.UserService;

import java.security.Principal;

/**
 * Created by Volio on 2016/12/18.
 */
@Controller
@RequestMapping("/account")
public class UserController {

    private final ChannelService channelService;
    private final UserService userService;
    private final StreamEngine streamEngine;

    @Autowired
    public UserController(ChannelService channelService, UserService userService, StreamEngine streamEngine) {
        this.channelService = channelService;
        this.userService = userService;
        this.streamEngine = streamEngine;
    }

    @GetMapping
    public String getAccountIndex(Model model, Principal principal) {
        User user = userService.getUserInfo(principal.getName());
        if (user.isStreamer()) {
            Channel channel = channelService.findOne(user);
            String publishUrl = streamEngine.getPublishUrl(channel.getStreamKey());
            model.addAttribute("channel", channel);
            model.addAttribute("publishUrl", publishUrl);
        }
        model.addAttribute("user", user);
        model.addAttribute("title", "用户中心");
        return "user/index";
    }
}
