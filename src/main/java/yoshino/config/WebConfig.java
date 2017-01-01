package yoshino.config;

import com.qiniu.pili.Client;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.core.env.Environment;
import yoshino.engine.StreamEngine;
import yoshino.support.pili.PiliEngine;

/**
 * Created by Volio on 2016/12/18.
 */
@Configuration
@PropertySource("classpath:config/pili.properties")
public class WebConfig {

    @Bean
    public StreamEngine streamEngine(Environment env) {
        PiliEngine piliEngine = new PiliEngine();
        Client client = new Client(env.getProperty("accessKey"), env.getProperty("secretKey"));
        piliEngine.setClient(client);
        piliEngine.setHubName(env.getProperty("hubName"));
        piliEngine.setHub(client.newHub(env.getProperty("hubName")));
        piliEngine.setRTMPPublishUrl(env.getProperty("RTMPPublishUrl"));
        piliEngine.setRTMPPlayUrl(env.getProperty("RTMPPlayUrl"));
        piliEngine.setHLSPlayUrl(env.getProperty("HLSPlayUrl"));
        piliEngine.setHDLPlayUrl(env.getProperty("HDLPlayUrl"));
        piliEngine.setSnapshotPlayUrl(env.getProperty("SnapshotPlayUrl"));
        return piliEngine;
    }
}
